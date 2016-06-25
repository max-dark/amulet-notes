<?php

error_reporting(E_ALL);

require_once('viewer.class.php');

if ($argc < 2) {
    echo sprintf(
        'Usage: php %s location [path/to/files/]' . PHP_EOL,
        $argv[0]
    );
    exit();
}
echo Viewer::view(
    $argv[1],
    ($argc > 2) ? $argv[2] : Viewer::def_root
);
