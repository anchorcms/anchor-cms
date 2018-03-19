<?php

/**
 * image class
 * Provides tools to manipulate images
 */
class image
{
    /**
     * Bloat factor to calculate approximate GD memory usage
     */
    const BLOAT_FACTOR = 1.68;

    /**
     * Percentages to control the color palette calculation
     */
    const COLOR_PALETTE_RATIOS = [0.2, 0.7, 0.5];

    /**
     * Default output image format
     */
    const DEFAULT_FILE_TYPE = 'png';

    /**
     * Matrix used to sharpen an image
     */
    const MATRIX_SHARPEN = [
        [0.0, -1.0, 0.0],
        [-1.0, 5.0, -1.0],
        [0.0, -1.0, 0.0]
    ];

    /**
     * Matrix used to blur an image
     */
    const MATRIX_BLUR = [
        [1.0, 2.0, 1.0],
        [2.0, 4.0, 2.0],
        [1.0, 2.0, 1.0]
    ];

    /**
     * Source image
     *
     * @var resource
     */
    protected $sourceImage;

    /**
     * Source image width
     *
     * @var int
     */
    protected $sourceWidth;

    /**
     * Source image height
     *
     * @var int
     */
    protected $sourceHeight;

    /**
     * Destination image
     *
     * @var resource
     */
    protected $destinationImage;

    /**
     * image constructor
     *
     * @param resource $sourceImage  source image to work with
     * @param int      $sourceWidth  source image width in pixels
     * @param int      $sourceHeight source image height in pixels
     */
    public function __construct($sourceImage, $sourceWidth, $sourceHeight)
    {
        $this->sourceImage  = $sourceImage;
        $this->sourceWidth  = $sourceWidth;
        $this->sourceHeight = $sourceHeight;
    }

    /**
     * Opens an image file
     *
     * @param string $file path to file
     *
     * @return \image|bool false if the image doesn't exist, new image otherwise
     */
    public static function open($file)
    {
        if (file_exists($file) === false) {
            return false;
        }

        /** @var int $width */
        /** @var int $height */
        /** @var int $type */
        list($width, $height, $type) = getimagesize($file);

        if ($type === IMAGETYPE_PNG) {
            return new static(imagecreatefrompng($file), $width, $height);
        }

        if ($type === IMAGETYPE_JPEG) {
            return new static(imagecreatefromjpeg($file), $width, $height);
        }

        if ($type === IMAGETYPE_GIF) {
            return new static(imagecreatefromgif($file), $width, $height);
        }

        return false;
    }

    /**
     * Retrieves the image width
     *
     * @return int
     */
    public function width()
    {
        return $this->sourceWidth;
    }

    /**
     * Retrieves the image height
     *
     * @return int
     */
    public function height()
    {
        return $this->sourceHeight;
    }

    /**
     * Checks the available memory
     *
     * @param string $file   path to image file
     * @param int    $dst_w  new image width
     * @param int    $dst_h  new image height
     * @param float  $bloat  (optional) bloat factor - basically a safety value accounting
     *                       for GD's memory overhead to make sure we have enough memory
     *
     * @return bool|float false if not enough memory, required memory otherwise
     * @deprecated Use checkAvailableMemory instead
     */
    public function check_available_memory($file, $dst_w, $dst_h, $bloat = 1.68)
    {
        // Get maxmemory limit in Mb convert to bytes
        $max = ((int)ini_get('memory_limit') * 1024) * 1024;

        // get image size
        list($src_w, $src_h) = getimagesize($file);

        // Source GD bytes
        $src_bytes = ceil((($src_w * $src_h) * 3) * $bloat);

        // Target GD bytes
        $dst_bytes = ceil((($dst_w * $dst_h) * 3) * $bloat);

        $total = $src_bytes + $dst_bytes + memory_get_usage();

        if ($total > $max) {
            return false;
        }

        return $src_bytes + $dst_bytes;
    }

    /**
     * Checks whether there is enough memory available to perform a GD operation
     *
     * @param string $path   path to image file
     * @param int    $width  new image width
     * @param int    $height new image height
     * @param float  $bloat  (optional) bloat factor - basically a safety value accounting
     *                       for GD's memory overhead to make sure we have enough memory
     *
     * @return bool|float false if not enough memory, required memory otherwise
     */
    public function checkAvailableMemory($path, $width, $height, $bloat = self::BLOAT_FACTOR)
    {
        // Get maximum memory limit in MB, convert it to bytes
        $availableMemory = ((int)ini_get('memory_limit') * 1024) * 1024;

        // get image size
        list($originalWidth, $originalHeight) = getimagesize($path);

        // source GD bytes
        $sourceBytes = ceil((($originalWidth * $originalHeight) * 3) * $bloat);

        // target GD bytes
        $destinationBytes = ceil((($width * $height) * 3) * $bloat);

        // calculate required memory for loading the source and destination bytes
        // plus the current memory usage
        $requiredMemory = $sourceBytes + $destinationBytes + memory_get_usage();

        if ($requiredMemory > $availableMemory) {
            return false;
        }

        return $sourceBytes + $destinationBytes;
    }

    /**
     * Sharpens an image
     *
     * @return \image
     */
    public function sharpen()
    {
        // calculate the divisor
        $divisor = array_sum(array_map('array_sum', self::MATRIX_SHARPEN));

        // apply the matrix
        imageconvolution($this->getImage(), self::MATRIX_SHARPEN, $divisor, 0);

        return $this;
    }

    /**
     * Retrieves the image. Uses the destination image if available
     *
     * @return resource
     */
    protected function getImage()
    {
        return (is_resource($this->destinationImage)
            ? $this->destinationImage
            : $this->sourceImage
        );
    }

    /**
     * Blurs an image
     *
     * @return \image
     */
    public function blur()
    {
        // define matrix
        $gaussian = self::MATRIX_BLUR;

        // calculate the divisor
        $divisor = array_sum(array_map('array_sum', $gaussian));

        // apply the matrix
        imageconvolution($this->getImage(), $gaussian, $divisor, 0);

        return $this;
    }

    /**
     * Turns an image to grayscale
     *
     * @return \image
     */
    public function grayscale()
    {
        imagefilter($this->getImage(), IMG_FILTER_GRAYSCALE);

        return $this;
    }

    /**
     * Resizes an image
     *
     * @param int $width  new image width
     * @param int $height new image height
     *
     * @return \image
     */
    public function resize($width, $height)
    {
        // TODO: Unused variable
        $ratio = 0;

        // landscape
        if ($this->sourceWidth > $this->sourceHeight) {
            $ratio  = $width / $this->sourceWidth;
            $height = $this->sourceHeight * $ratio;
        }

        // portrait
        if ($this->sourceWidth < $this->sourceHeight) {
            $ratio = $height / $this->sourceHeight;
            $width = $this->sourceWidth * $ratio;
        }

        // square: use smaller value as the match
        if ($this->sourceWidth == $this->sourceHeight) {
            if ($width > $height) {
                $width = $height;
            } else {
                $height = $width;
            }
        }

        $this->destinationImage = imagecreatetruecolor($width, $height);

        $params = [
            'dst_image' => $this->destinationImage,
            'src_image' => $this->sourceImage,
            'dst_x'     => 0,
            'dst_y'     => 0,
            'src_x'     => 0,
            'src_y'     => 0,
            'dst_w'     => $width,
            'dst_h'     => $height,
            'src_w'     => $this->sourceWidth,
            'src_h'     => $this->sourceHeight
        ];

        call_user_func_array('imagecopyresampled', array_values($params));

        return $this;
    }

    /**
     * Crops an image
     *
     * @param int $width            new image width
     * @param int $height           new image height
     * @param int $horizontalOffset new image left coordinate
     * @param int $verticalOffset   new image bottom coordinate
     *
     * @return \image
     */
    public function crop($width, $height, $horizontalOffset, $verticalOffset)
    {
        // if the source image is square use smaller destination size
        // @link https://medium.com/@srccd/the-move-to-anchor-cms-1e4e261f15a7
        if ($this->sourceWidth == $this->sourceHeight) {
            if ($width > $height) {
                $width = $height;
            } else {
                $height = $width;
            }
        }

        $this->destinationImage = imagecreatetruecolor($width, $height);

        $params = [
            'dst_image' => $this->destinationImage,
            'src_image' => $this->sourceImage,

            'dst_x' => 0,
            'dst_y' => 0,
            'src_x' => $horizontalOffset,
            'src_y' => $verticalOffset,

            'dst_w' => $width,
            'dst_h' => $height,
            'src_w' => $width,
            'src_h' => $height
        ];

        call_user_func_array('imagecopyresampled', array_values($params));

        return $this;
    }

    /**
     * Calculate a color palette from an image
     * TODO: Currently I don't know exactly what this function does or how the percentages really work.
     *
     * @param array $percentages controls the colors picked
     *
     * @return array hex color codes
     */
    public function palette($percentages = self::COLOR_PALETTE_RATIOS)
    {
        $rgb = [];
        $r   = [];
        $g   = [];
        $b   = [];

        // Now set dimensions on where to pull color values from (based on percentages).
        $dimensions[] = ($this->sourceWidth - ($this->sourceWidth * $percentages[0]));
        $dimensions[] = ($this->sourceHeight - ($this->sourceHeight * $percentages[0]));

        $dimensions[] = ($this->sourceWidth - ($this->sourceWidth * $percentages[0]));
        $dimensions[] = ($this->sourceHeight - ($this->sourceHeight * $percentages[1]));

        $dimensions[] = ($this->sourceWidth - ($this->sourceWidth * $percentages[1]));
        $dimensions[] = ($this->sourceHeight - ($this->sourceHeight * $percentages[1]));

        $dimensions[] = ($this->sourceWidth - ($this->sourceWidth * $percentages[1]));
        $dimensions[] = ($this->sourceHeight - ($this->sourceHeight * $percentages[0]));

        $dimensions[] = ($this->sourceWidth - ($this->sourceWidth * $percentages[2]));
        $dimensions[] = ($this->sourceHeight - ($this->sourceHeight * $percentages[2]));

        // Here we'll pull the color values of certain pixels around the image
        // based on our dimensions set above.
        for ($k = 0; $k < 10; $k++) {
            $newk  = $k + 1;
            $rgb[] = imagecolorat($this->sourceImage, $dimensions[$k], $dimensions[$newk]);
            $k++;
        }

        // Almost done! Now we need to get the individual red, green and blue values for our colors.
        foreach ($rgb as $colorValue) {
            $r[] = dechex(($colorValue >> 16) & 0xFF);
            $g[] = dechex(($colorValue >> 8) & 0xFF);
            $b[] = dechex($colorValue & 0xFF);
        }

        return [
            strtoupper($r[0] . $g[0] . $b[0]),
            strtoupper($r[1] . $g[1] . $b[1]),
            strtoupper($r[2] . $g[2] . $b[2]),
            strtoupper($r[3] . $g[3] . $b[3]),
            strtoupper($r[4] . $g[4] . $b[4])
        ];
    }

    /**
     * Output an image
     * TODO: Why is path nullable? If omitted, image* functions will just output the plain stream...
     *
     * @param string      $type (optional) file extension
     * @param string|null $path (optional) path to file
     *
     * @return void
     */
    public function output($type = self::DEFAULT_FILE_TYPE, $path = null)
    {
        if ($type == 'png') {
            imagepng($this->destinationImage, $path, 9);
        } elseif ($type == 'jpeg' or $type == 'jpg') {
            imagejpeg($this->destinationImage, $path, 75);
        } elseif ($type == 'gif') {
            imagegif($this->destinationImage, $path);
        }

        imagedestroy($this->destinationImage);
    }
}
