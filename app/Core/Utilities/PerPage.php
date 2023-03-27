<?php

namespace App\Core\Utilities;

class PerPage
{
    /*
    |--------------------------------------------------------------------------
    | Per Page
    |--------------------------------------------------------------------------
    |
    | This class contains the CMS' Pagination allowed values and handles giving them to it.
    |
    */

    public static $default = 10;

    public static $allowed = [
        5 => '5 / Page',
        10 => '10 / Page',
        20 => '20 / Page',
        50 => '50 / Page',
    ];

    /**
     * Set
     */
    public static function set($num)
    {
        if (array_key_exists($num, self::$allowed)) {
            session()->put('paginate_value', $num);
        } else {
            session()->put('paginate_value', self::$default);
        }
    }

    /**
     * Get
     */
    public static function get()
    {
        if (session('paginate_value')) {
            return session('paginate_value');
        }

        return self::$default;
    }

    /**
     * Select box
     */
    public static function selectbox($name, $id, $data, $selected_value, $more_attributes = '', $more_classes = '')
    {
        $out = '<select name="' . $name . '" id="' . $id . '" class="select2 form-control ' . $more_classes . '" ' . $more_attributes . '>' . chr(10);

        if (count($data) > 0) {
            foreach ($data as $k => $v) {
                $out .= '<option value="' . e($k) . '"';
                if ($k == $selected_value) $out .= ' selected="selected"';
                $out .= '>' . e($v) . '</option>' . chr(10);
            }
        }
        $out .= '</select>' . chr(10);
        return $out;
    }
}
