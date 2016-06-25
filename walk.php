<?php

error_reporting(E_ALL);

require_once('viewer.class.php');
require_once('menu.class.php');

if ($argc < 2) {
    echo sprintf(
        'Usage: php %s location [path/to/files/]' . PHP_EOL,
        $argv[0]
    );
    exit();
}
$location = $argv[1];
$root_dir = ($argc > 2) ? $argv[2] : Viewer::def_root;
do {
    echo Viewer::view(
        $location,
        $root_dir
    );
    echo print_r(Viewer::get_data(), true);
    echo Viewer::get_info().PHP_EOL;
    $location = Menu::choice(Viewer::get_d());
} while ($location);
