<?php

require_once('loader.class.php');

class Menu
{
    /**
     * @param $menu array
     * @return string
     */
    static function choice($menu)
    {
        $keys = array_keys($menu);
        $id = 0;
        foreach ($keys as $key) {
            if (in_array($key, [Loader::l_key, 0, 1, 2]))
                echo sprintf("..:: %s ::..\n", $menu[$key]);
            else
                echo sprintf("%d %s\n", $id, $menu[$key]);
            $id++;
        }
        echo sprintf("%d %s\n", 0, '[exit]');
        $id = intval(fgets(STDIN));
        return $id == 0 ? '' : $keys[$id];
    }
}