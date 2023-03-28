<?php
    /*
    |--------------------------------------------------------------------------
    | Helper Functions
    |--------------------------------------------------------------------------
    |
    | This file contains the helper functions.
    |
    */

/**
 * Show performance Stats
 */
function show_stats() {
    $ret = 'Time ellapsed: ' . round(microtime(true) - LARAVEL_START, 3) . ' s' . chr(10);
    $ret .= 'Memory get usage: ' . round(memory_get_usage(true) / 1000000, 3) . ' MB' . chr(10);
//    $ret .= 'Memory usage pid: ' . memory_usage() . chr(10);
    $ret .= 'Memory get peak usage: ' . round(memory_get_peak_usage(true) / 1000000, 3) . ' MB' . chr(10);

    return $ret;
}

/**
 * Delete Form
 */
function delete_form($url, $btn_class = 'btn btn-secondary margin') {
    return '<form action="' . e($url) . '"method="POST">'
        . csrf_field()
        . method_field('DELETE')
        . '<button type="submit" class="' . $btn_class . ' confirm"><span class="fa fa-trash"></span> &nbsp;Delete</button>'
        . '</form>';
}

/**
 * Select box
 */
function selectbox($name, $id, $data, $selected_value, $more_attributes = '', $more_classes = '') {
    $out = '<select name="' . $name . '" id="' . $id . '" class="custom-select form-control ' . $more_classes . '" ' . $more_attributes . '>' . chr(10);

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

/**
 * Errors' Form
 */
function errors_form($errors, $field) {
    $out = '';

    if ($errors->has($field)) {
        $out .= '<div class="invalid-feedback">';

        foreach ($errors->get($field) as $error) {
            $out .= ' ' . e(__($error)) . '<br>';
        }

        $out .= '</div>';
    }

    return $out;
}
