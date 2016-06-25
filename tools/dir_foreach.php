<?php
/**
 * Вызывает $callback для каждого элемента(кроме списка $exclude) в $dir_name
 *
 * @param $dir_name string directory for scan
 * @param $callback callable callback
 * @param $exclude array list to exclude
 */
function dir_foreach($dir_name, $callback, $exclude = []) {
    $dh = opendir($dir_name);
    /* @var string $f_name */
    while (($f_name = readdir($dh)) !== false) {
        if(!in_array($f_name, $exclude)) {
            $callback($f_name);
        }
    }
    closedir($dh);
}
