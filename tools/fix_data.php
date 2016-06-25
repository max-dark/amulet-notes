<?php

/**
 * @param string $raw_data
 * @return string;
 */
function fix_data($raw_data) {
    return preg_replace_callback(
        '/s:(?:\d+):"(.*?)";/',
        function($m) {
            return "s:" . strlen($m[1]) . ":\"{$m[1]}\";";
        },
        $raw_data
    );
}
