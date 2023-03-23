<?php

namespace App\Core\Utilities;

/**
 * DateFormat
 */
class DatetimeFormat
{
    /**
     * Formats
     */
    public static $formats = [
        'Y-m-d H:i',
        'Y-m-d g:i A',
        'd/m/Y H:i',
        'd/m/Y g:i A',
        'm/d/Y H:i',
        'm/d/Y g:i A',
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
