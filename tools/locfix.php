<?php
require_once 'dir_foreach.php';
require_once 'fix_data.php';

if($argc == 2) {
    $d_name = $argv[1];
    dir_foreach($d_name, function($f_name) use ($d_name) {
        $name = $d_name.$f_name;
        echo "fix $name ...";
        if(!file_exists($name)) {
            echo "cant open\n";
            return;
        }
        file_put_contents($name,
            fix_data(
                file_get_contents($name)
            )
        );
        echo PHP_EOL;
    },['.', '..', '.htaccess']);
}
else {
    echo sprintf("Usage: %s <path/to/data/>\n", $argv[0]);
}