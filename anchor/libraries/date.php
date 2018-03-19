<?php

use System\config;

/**
 * date class
 * Formats date strings for the frontend and the database
 */
class date
{
    /**
     * Default display date format
     */
    const DEFAULT_DATE_FORMAT = 'jS F, Y';

    /**
     * Format a date as per users timezone and format
     *
     * @param string      $date   date to format
     * @param string|null $format (optional) format string
     *
     * @return string
     */
    public static function format($date, $format = null)
    {
        // set the meta format
        if (is_null($format)) {
            $format = Config::meta('date_format', self::DEFAULT_DATE_FORMAT);
        }

        $date = new DateTime($date, new DateTimeZone('GMT'));

        $date->setTimezone(new DateTimeZone(Config::app('timezone')));

        return $date->format($format);
    }

    /**
     * All database dates are stored as GMT
     *
     * @param string $date
     *
     * @return string
     */
    public static function mysql($date)
    {
        $date = new DateTime($date, new DateTimeZone('GMT'));

        return $date->format('Y-m-d H:i:s');
    }
}
