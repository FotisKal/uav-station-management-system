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
