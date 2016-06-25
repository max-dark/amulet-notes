<?php
require_once 'fix_data.php';

$raw_data = implode('', file($argv[1]));
$data = unserialize($raw_data);
if(!$data) {
    $raw_data = fix_data($raw_data);
    $data = unserialize($raw_data);
}
print_r($data);