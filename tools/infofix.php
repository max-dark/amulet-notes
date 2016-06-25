<?php

require_once 'dir_foreach.php';

$lst = [];
$dname = 'amulet/game/loc_f/';

dir_foreach($dname,
function($fname) use(&$lst, $dname) {
    $fullname = $dname.$fname;
    $info = explode("\n", file_get_contents($fullname));
    if (count($info) > 1) {
        $info = trim(implode('',$info));
        file_put_contents($fullname, $info);
        $lst[] = $fullname;
    }
}
,['.', '..', '.htaccess']);

print_r($lst);
