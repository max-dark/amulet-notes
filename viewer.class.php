<?php

require_once('loader.class.php');

class Viewer implements Loader
{
    // Путь к файлам игры
    const def_root = 'amulet/game/';
    // директория с таймерами локаций
    const timer_dir = 'loc_t/';
    // директория с текущим состоянием локаций
    const work_dir = 'loc_i/';
    // директория с описанием локаций
    const info_dir = 'loc_f/';

    static private $data = [];
    static private $raw_d ='';
    static private $info = '';
    static private $timers = [];

    /**
     * @return string
     */
    public static function get_info()
    {
        return self::$info;
    }

    /**
     * @return array
     */
    public static function get_data()
    {
        return self::$data;
    }

    /**
     * @return array
     */
    public static function get_timers()
    {
        return self::get(self::$timers, Loader::t_key);
    }

    /**
     * @param array $arr
     * @param string $key
     * @param array $default
     * @return array
     */
    static private function get($arr, $key, $default = [])
    {
        return array_key_exists($key, $arr) ? $arr[$key] : $default;
    }

    /**
     * @param string $location
     * @param string $root_dir
     * @return string
     * @throws Exception
     */
    static function view($location, $root_dir)
    {
        list($log, self::$data, self::$info) = self::load($location, $root_dir);
        return implode('', $log);
    }

    /**
     * @param string $location
     * @param string $root_dir
     * @return array
     * @throws Exception
     */
    public static function load($location, $root_dir)
    {
        $log = [];
        $log[] = sprintf(
            "Loading %s from %s\n",
            $location,
            $root_dir
        );
        $info_file = $root_dir . self::info_dir . $location;

        list($raw_data, $log) = self::load_data($location, $root_dir, $log);

        list($data, $log) = self::parse_data($raw_data, $log);

        $info = file_exists($info_file) ?
            file_get_contents($info_file) :
            sprintf(
                "cant find info for %s",
                $location
            );
        return [$log, $data, $info];
    }

    /**
     * @param string $location
     * @param string $root_dir
     * @param array $log
     * @return array
     * @throws Exception
     */
    private static function load_data($location, $root_dir, $log)
    {
        $work_file = $root_dir . self::work_dir . $location;
        $timer_file = $root_dir . self::timer_dir . $location;

        $raw_data = '';
        $have_timers = file_exists($timer_file);

        if ($have_timers) {
            $log[] = "load from timers file\n";
            $raw_data = file_get_contents($timer_file);
            list(self::$timers, $log) = self::parse_data($raw_data, $log);
        }
        if (!file_exists($work_file)) {
            $log[] = sprintf("file %s not found\n", $work_file);
            if (!$have_timers) {
                $log[] = sprintf(
                    "cant find location %s\n",
                    $location
                );
                throw new \Exception(implode('', $log));
            }
        } else {
            $raw_data = file_get_contents($work_file);
        }
        return [$raw_data, $log];
    }

    /**
     * @param string $raw_data
     * @param array $log
     * @return array
     * @throws Exception
     */
    private static function parse_data($raw_data, $log)
    {
        $data = @unserialize($raw_data);
        if (!$data) {
            $log[] = "unserialize fail. try recovery\n";
            // попытка восстановить длины строк
            // нужна, если правили файл в ручную
            $raw_data = preg_replace_callback(
                '/s:(?:\d+):"(.*?)";/',
                function ($m) {
                    return "s:" . strlen($m[1]) . ":\"{$m[1]}\";";
                },
                $raw_data
            );
            $data = @unserialize($raw_data);
            if (!$data) {
                $log[] = "recovery fail. exit\n";
                throw new \Exception(implode('', $log));
            }
        }
        if (array_key_exists(Loader::d_key, $data)) {
            $data[Loader::raw_d_key] = $data[Loader::d_key];
            $data[Loader::d_key] = self::parse_links($data[Loader::d_key]);
        }
        return [$data, $log];
    }

    /**
     * @param string $d
     * @return array
     */
    static private function parse_links($d)
    {
        $data = [];
        $tmp = explode('|', $d);
        for ($i = 0; $i < count($tmp); $i += 2) {
            $data[$tmp[$i + 1]] = $tmp[$i];
        }
        return $data;
    }

    /**
     * @return array
     */
    static function get_d()
    {
        return self::get(self::$data, Loader::d_key);
    }
    /**
     * @return array
     */
    static function get_raw_d()
    {
        return self::get(self::$data, Loader::raw_d_key);
    }

    /**
     * @return array
     */
    static function get_i()
    {
        return self::get(self::$data, Loader::i_key);
    }

    /**
     * @return array
     */
    static function get_t()
    {
        return self::get(self::$data, Loader::t_key);
    }
}
