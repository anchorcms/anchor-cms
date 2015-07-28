<?php
namespace System;

/**
 * Security library -- a swiss army knife of cryptography utilities
 *
 * Created for Anchor CMS
 * Copyright 2015 Paragon Initiative Enterprises <https://paragonie.com>
 * License: GPL 3.0 (for the sake of consistency)
 */
class Security
{
    /**
     * Generate a string of random bytes from the appropriate CSPRNG
     *
     * Adopted from paragonie/random_compat
     *
     * @param int $bytes positive integer
     * @return string
     */
    public static function randomBytes($bytes)
    {
        static $fp = null;
        static $method = null;

        $bytes = (int) $bytes;
        if ($bytes < 1) {
            throw new Exception('Security::randomBytes() expects a positive integer');
        }
        /**
         * Select the most appropriate method for our current platform
         */
        if (!$method) {
            if (function_exists('random_bytes')) {
                $method = 'random_bytes';
            } else {
                if (!ini_get('open_basedir')) {
                    if (file_exists('/dev/arandom') && !is_link('/dev/arandom') && filetype('/dev/arandom') === 'char') {
                        $method = 'arandom';
                    } elseif (file_exists('/dev/urandom') && filetype('/dev/urandom') === 'char') {
                        $method = 'urandom';
                    }
                }
                if (!$method) {
                    if (function_exists('mcrypt_create_iv') && version_compare(PHP_VERSION, '5.3.7') >= 0) {
                        $method = 'mcrypt';
                    } elseif (extension_loaded('com_dotnet')) {
                        $method = 'capicom';
                    } else {
                        $method = 'openssl';
                    }
                }
            }
        }
        /**
         * Now let's use the method to determine
         */
        switch ($method) {
            case 'random_bytes':
                // PHP 7+
                return random_bytes($bytes);
            case 'arandom':
                // *BSD
                return self::randomBytesFromFile($bytes, '/dev/arandom');
            case 'urandom':
                // GNU/Linux, OS X, etc.
                return self::randomBytesFromFile($bytes, '/dev/urandom');
            case 'capicom':
                // Windows
                return self::randomBytesFromCapicom($bytes);
            case 'mcrypt':
                // ext-mcrypt
                return mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM);
            default:
                // PHP 5.3.0+
                return openssl_random_pseudo_bytes($bytes);
        }
    }

    /**
     * Generate random bytes from the appropriate Windows API
     *
     * @param int $bytes How many bytes?
     *
     * @return string
     */
    public static function randomBytesFromCapicom($bytes)
    {
        $buf = '';
        $util = new COM('CAPICOM.Utilities.1');
        $execCount = 0;
        /**
         * Let's not let it loop forever. If we run N times and fail to
         * get N bytes of random data, then CAPICOM has failed us.
         */
        do {
            $buf .= base64_decode($util->GetRandom($bytes, 0));
            if (self::safeStrlen($buf) >= $bytes) {
                /**
                 * Return our random entropy buffer here:
                 */
                return self::safeSubstr($buf, 0, $bytes);
            }
            ++$execCount;
        } while ($execCount < $bytes);
        /**
         * If we reach here, PHP has failed us.
         */
        throw new Exception(
            'PHP failed to generate random data.'
        );
    }

    /**
     * Generate random bytes from a source file (typically /dev/urandom). You
     * can use this with /dev/zero for unit testing purposes.
     *
     * @param int $bytes How many bytes?
     * @param string $file Which file?
     *
     * @return string
     */
    public static function randomBytesFromFile($bytes, $file)
    {
        static $fd = [];

        if (!isset($fd[$file])) {
            $fd[$file] = fopen($file, 'rb');
            if ($fd[$file] === false) {
                throw new Exception("Could not open {$file} to read bytes");
            }
            stream_set_read_buffer($fd[$file], 0);
        }
        $fp =& $fd[$file];

        /**
         * If we have a valid file descriptor, continue
         */
        if (!empty($fp)) {
            $remaining = $bytes;
            $buf = '';
            /**
             * We use fread() in a loop to protect against partial reads
             */
            do {
                $read = fread($fp, $remaining);
                if ($read === false) {
                    /**
                     * We cannot safely read from the file. Exit the
                     * do-while loop and trigger the exception condition
                     */
                    $buf = false;
                    break;
                }
                /**
                 * Decrease the number of bytes returned from remaining
                 */
                $remaining -= self::safeStrlen($read);
                $buf .= $read;
            } while ($remaining > 0);

            /**
             * Is our result valid?
             */
            if ($buf !== false) {
                if (self::safeStrlen($buf) === $bytes) {
                    /**
                     * Return our random entropy buffer here:
                     */
                    return $buf;
                }
            }
        }
        throw new Exception("PHP failed to generate random data.");
    }

    /**
     * Fetch a random integer between $min and $max inclusive
     *
     * Adapted from https://github.com/paragonie/random_compat
     *
     * @param int $min
     * @param int $max
     *
     * @throws Exception
     *
     * @return int
     */
    function randomInt($min, $max)
    {
        static $native = null;
        if ($native === null) {
            // PHP 7+
            $native = function_exists('random_int');
        }
        if ($native) {
            return random_int($min, $max);
        }
        /**
         * Type and input logic checks
         */
        if (!is_int($min)) {
            throw new Exception(
                'random_int(): $min must be an integer'
            );
        }
        if (!is_int($max)) {
            throw new Exception(
                'random_int(): $max must be an integer'
            );
        }
        if ($min > $max) {
            throw new Exception(
                'Minimum value must be less than or equal to the maximum value'
            );
        }
        if ($max === $min) {
            return $min;
        }

        /**
         * Initialize variables to 0
         *
         * We want to store:
         * $bytes => the number of random bytes we need
         * $mask => an integer bitmask (for use with the &) operator
         *          so we can minimize the number of discards
         */
        $attempts = $bits = $bytes = $mask = $valueShift = 0;

        /**
         * At this point, $range is a positive number greater than 0. It might
         * overflow, however, if $max - $min > PHP_INT_MAX. PHP will cast it to
         * a float and we will lose some precision.
         */
        $range = $max - $min;

        /**
         * Test for integer overflow:
         */
        if (!is_int($range)) {
            /**
             * Still safely calculate wider ranges.
             * Provided by @CodesInChaos, @oittaa
             *
             * @ref https://gist.github.com/CodesInChaos/03f9ea0b58e8b2b8d435
             *
             * We use ~0 as a mask in this case because it generates all 1s
             *
             * @ref https://eval.in/400356 (32-bit)
             * @ref http://3v4l.org/XX9r5  (64-bit)
             */
            $bytes = PHP_INT_SIZE;
            $mask = ~0;
        } else {
            /**
             * $bits is effectively ceil(log($range, 2)) without dealing with
             * type juggling
             */
            while ($range > 0) {
                if ($bits % 8 === 0) {
                   ++$bytes;
                }
                ++$bits;
                $range >>= 1;
                $mask = $mask << 1 | 1;
            }
            $valueShift = $min;
        }

        /**
         * Now that we have our parameters set up, let's begin generating
         * random integers until one falls between $min and $max
         */
        do {
            /**
             * The rejection probability is at most 0.5, so this corresponds
             * to a failure probability of 2^-128 for a working RNG
             */
            if ($attempts > 128) {
                throw new Exception(
                    'random_int: RNG is broken - too many rejections'
                );
            }

            /**
             * Let's grab the necessary number of random bytes
             */
            $randomByteString = random_bytes($bytes);
            if ($randomByteString === false) {
                throw new Exception(
                    'Random number generator failure'
                );
            }

            /**
             * Let's turn $randomByteString into an integer
             *
             * This uses bitwise operators (<< and |) to build an integer
             * out of the values extracted from ord()
             *
             * Example: [9F] | [6D] | [32] | [0C] =>
             *   159 + 27904 + 3276800 + 201326592 =>
             *   204631455
             */
            $val = 0;
            for ($i = 0; $i < $bytes; ++$i) {
                $val |= ord($randomByteString[$i]) << ($i * 8);
            }

            /**
             * Apply mask
             */
            $val &= $mask;
            $val += $valueShift;

            ++$attempts;
            /**
             * If $val overflows to a floating point number,
             * ... or is larger than $max,
             * ... or smaller than $int,
             * then try again.
             */
        } while (!is_int($val) || $val > $max || $val < $min);
        return (int) $val;
    }

    /**
     * Generate a random string
     *
     * @param int $length How many characters long should it be?
     * @param string $alphabet Which characters should we allow?
     *
     * @return string
     */
    public static function randomString($length = 16, $alphabet = 'abcdefghijklmnopqrstuvwxyz')
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Length must be a positive integer');
        }
        $str = '';
        $alphmax = self::ourStrlen($alphabet) - 1;
        if ($alphamax < 1) {
            throw new InvalidArgumentException('Invalid alphabet');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $alphabet[self::randomInt(0, $alphmax)];
        }
        return $str;
    }

    /**
     * Compare two strings without timing side-channels
     *
     * @staticvar boolean $native Use native hash_equals()? (PHP 5.6+ only)
     *
     * @param string $expected string (raw binary)
     * @param string $given string (raw binary)
     * @return boolean
     */
    public static function hashEquals($expected, $given)
    {
        static $native = null;
        if ($native === null) {
            $native = \function_exists('hash_equals');
        }
        if ($native) {
            return hash_equals($expected, $given);
        }

        if (self::safeStrlen($expected) !== self::safeStrlen($given)) {
            return false;
        }
        $blind = self::randomBytes(32);
        $message_compare = hash_hmac('sha256', $given, $blind);
        $correct_compare = hash_hmac('sha256', $expected, $blind);
        return $correct_compare === $message_compare;
    }

    /**
     * Safe string length
     *
     * @staticvar boolean $exists
     * @param string $str
     * @return int
     */
    public static function safeStrlen($str)
    {
        static $exists = null;
        if ($exists === null) {
            $exists = function_exists('mb_strlen');
        }
        if ($exists) {
            $length = mb_strlen($str, '8bit');
            return $length;
        } else {
            return strlen($str);
        }
    }

    /**
     * Safe substring
     *
     * @staticvar boolean $exists
     * @param string $str
     * @param int $start
     * @param int $length
     * @return string
     */
    public static function safeSubstr($str, $start, $length = null)
    {
        static $exists = null;
        if ($exists === null) {
            $exists = function_exists('mb_substr');
        }
        if ($exists)
        {
            // mb_substr($str, 0, NULL, '8bit') returns an empty string on PHP
            // 5.3, so we have to find the length ourselves.
            if (!isset($length)) {
                if ($start >= 0) {
                    $length = self::safeStrlen($str) - $start;
                } else {
                    $length = -$start;
                }
            }

            return mb_substr($str, $start, $length, '8bit');
        }

        // Unlike mb_substr(), substr() doesn't accept NULL for length
        if (isset($length)) {
            return substr($str, $start, $length);
        } else {
            return substr($str, $start);
        }
    }
}
