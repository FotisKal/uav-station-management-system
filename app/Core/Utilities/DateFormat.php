<?php

namespace App\Core\Utilities;

/**
 * DateFormat
 */
class DateFormat
{
    /**
     * Formats
     */
    public static $formats = [
        'Y-m-d',
        'd/m/Y',
        'm/d/Y',
    ];

    /**
     * To List
     */
    public static function toList()
    {
        $data = [];
        foreach (self::$formats as $format) {
            $data[$format] = date($format);
        }
        return $data;
    }
}
