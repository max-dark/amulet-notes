<?php
require_once "char.class.php";

/**
 * @param string $name
 */
function load_loc($name) {}
function docrim($a, $b) {}
/**
 * @param string $location
 * @param string $to
 * @param string $message
 * @param string $exclude1
 * @param string $exclude2
 * @param string $delim
 */
function add_journal($location, $to, $message, $exclude1 = "", $exclude2 = "", $delim = "|") {}
/**
 * @param string $location Локация
 * @param string $from кто атакует
 * @param string $to кого атакуют
 * @param string $magic тип магии(?)
 * @param int $answer флаг что атака в ответ(?)
 * @param int $rmagic хз
 * @param string $priem тип приема(?)
 * @param string $ptitle название приема(?)
 */
function attack(
    $location, $from, $to,
    $magic = '', $answer = 1,
    $rmagic = 0, $priem = "", $ptitle =""
) {
    global $location_items, $loc_tt, $PHP_SELF, $game;
// проверки
    if ((!is_user($from) && !is_npc($from)) ||
        (!is_user($to) && !is_npc($to)) ||
        !isset($location_items[$location][$from]) ||
        !isset($location_items[$location][$to]) ||
        $from == $to)
        return;
    $fchar = explode("|", $location_items[$location][$from]["char"]);
    if (is_user($from) && $fchar[8]) {
        if ($answer)
            add_journal($location, $from, "Вы призрак");
        return;
    }

    $loct = $location;
    $aloct = explode("|", $loc_tt[$location]["d"]);
    $tchar = explode("|", $location_items[$loct][$to]["char"]);
    if (is_user($to) && $tchar[8]) {
        if ($answer) add_journal($location, $from, "Нельзя атаковать призрака");
        return;
    }
    $twar = explode("|", $location_items[$loct][$to]["war"]);

    if ($fchar[6] - time() > 300)
        $fchar[6] = time() - 1;
    if (!$rmagic && time() <= $fchar[6]) {
        if ($answer)
            add_journal($location, $from, "Вы должны отдохнуть " . round($fchar[6] - time() + 1) . " сек");
        return;
    }
    if ($location_items[$location][$to]["def"])
        $tdef = explode("|", $location_items[$location][$to]["def"]);
    else
        $tdef = array("", "", 0);
    if ($tdef[2] && time() > $tdef[2]) {
        $location_items[$location][$to]["def"] = "";
        $tdef = array("", "", 0);
    }
    if ($ptitle)
        $ptitle = " (" . $ptitle . ")";
    $tloc = explode("x", $location);
    if ($magic)
        $fwar = explode("|", $magic);
    else
        $fwar = explode("|", $location_items[$location][$from]["war"]);
    if ($answer) {
        $fchar[6] = time() + $fwar[3];
        $location_items[$location][$from]["char"] = implode("|", $fchar);
    }
    if ($fwar[12] == "мaгиeй")
        $fwar[12] = "магией";    //eng a,e
    if ($rmagic || $fwar[12] == "магией" || $fwar[12] == "молнией") {
        if ($tdef[0] == "p.d.z" && rand(0, 100) <= $tdef[3] * 0.10) {
            if (substr($location_items[$location][$from]["def"], 0, 5) == "p.d.c") {
                $fdef = explode("|", $location_items[$location][$from]["def"]);
                $fdef = $fdef[3];
            } else $fdef = 0;
            if (rand(0, 100) > $fdef)
                $fwar[0] = 0;
        }
        if (substr($location_items[$location][$from]["def"], 0, 5) == "p.d.c")
            $location_items[$location][$from]["def"] = "";
        if ($tdef[0] == "p.d.z") {
            $location_items[$location][$to]["def"] = "";
            $t2 = $tdef[1];
        }
        $uklon = $twar[9];
        $parring = $twar[10];
        $shield = $twar[11];
    } else {
        $uklon = $twar[6];
        $parring = $twar[7];
        $shield = $twar[8];
        if ($tdef[0] == "p.d.u" && $fwar[4]) {
            if (rand(0, 100) <= $tdef[3]) $uklon += 35;
            $location_items[$location][$to]["def"] = "";
            $t2 = $tdef[1];
        }
        if ($tdef[0] == "p.d.re") {
            if (rand(0, 100) <= $tdef[3]) $uklon += 20;
            $location_items[$location][$to]["def"] = "";
            $t2 = $tdef[1];
        }
        if ($tdef[0] == "p.d.p") {
            if (rand(0, 100) <= $tdef[3]) $parring *= 2;
            $location_items[$location][$to]["def"] = "";
            $t2 = $tdef[1];
        }
    }

    if ($priem == "p.g" && $tdef[0] == "p.d.g") {
        if (rand(0, 100) <= $tdef[3]) {
            $fwar[1] = 0;
            $fwar[2] = 0;
        }
        $location_items[$location][$to]["def"] = "";
        $t2 = $tdef[1];
    }
    if (substr($location_items[$location][$from]["def"], 0, 6) == "p.d.re") {
        $fwar[1] = round($fwar[1] * 0.6);
        $fwar[2] = round($fwar[2] * 0.6);
    }
    if ($tdef[0] == "p.d.o" && !$rmagic) {
        if (rand(0, 100) <= $tdef[3]) {
            $fwar[1] = round($fwar[1] * 0.4);
            $fwar[2] = round($fwar[2] * 0.4);
        }
        $t2 = $tdef[1];
    }
    if ($priem == "p.n" && $tdef[0] == "p.d.n" ||
        $priem == "p.r" && $tdef[0] == "p.d.r" ||
        $priem == "p.vs" && $tdef[0] == "p.d.s" ||
        $priem == "p.vw" && strpos($location_items[$location][$to]["equip"], "i.a.s.") !== false)
        $t2 = $tdef[1];
    if ($t2)
        $t2 = " (" . $t2 . ")";


// крим если атакует не крима или животное в городе
    $fstp = strpos($fchar[0], "*");
    $tstp = strpos($tchar[0], "*");
    if ($fstp === false)
        $clan1 = "";
    else
        $clan1 = substr($fchar[0], $fstp + 1, strrpos($fchar[0], "*") - $fstp - 1);
    if ($tstp === false)
        $clan = "";
    else
        $clan = substr($tchar[0], $tstp + 1, strrpos($tchar[0], "*") - $tstp - 1);
    $fcrim = $fchar[9] || substr($from, 0, 4) == "n.c.";

    $tcrim = $tchar[9] || substr($to, 0, 4) == "n.c." || $to == "n.w.Veelzevul" || $to == "n.whitewolf" || $game["floc"] == $location && $game["fid"] == $to;
    if ($tloc[2] >= 1099) {
        $tcrim = $tcrim || $tchar[14] == "p" || substr($to, 0, 4) == "n.p." || $fchar[14] == "p" && $tchar[14] == "t";
    }
    if ($fchar[13])
        $wife = $to == substr($fchar[13], 0, strlen($to));
    else
        $wife = 0;
    if ($from != $to && !$fcrim && $tchar[7] != $from && !$tcrim && (!$clan1 || ($clan1 && $clan1 != $clan)) && !$wife && $from != "u.qv" && $to != "u.qv") {
        if (isset($location_items[$location][$to]["owner"]))
            docrim($location, $from, "живодер");    //$aloct[1] && substr($to,0,4)=="n.a." ||
        else if (substr($to, 0, 4) != "n.a.")
            docrim($location, $from, "бандит");
        $fchar = explode("|", $location_items[$location][$from]["char"]);
    }

// патроны
    if ($fwar[14])
        if (strpos($location_items[$location][$from]["items"], $fwar[14] . ":") !== false) {
            additem($location, $from, "", $fwar[14], 1, "items", "", 0);
            if (strpos($location_items[$location][$from]["items"], $fwar[14] . ":") === false) add_journal($location, $from, "Боеприпасы кончились");
        } else {
            add_journal($location, $from, "Нет боеприпасов");
            return;
        }
// цель конник
    if (substr($to, 0, 2) == "u." && $tchar[12] && !$rmagic && $fwar[12] != "магией")
        $fwar[0] -= 10;
    if (substr($to, 0, 4) == "n.c.")
        if (strpos($location_items[$location][$from]["equip"], "i.a.m.vlast") !== false) {
            $fwar[1] = $fwar[1] * 2;
            $fwar[2] = $fwar[2] * 2;
        }

// заклинание сорвалось?
    if ($fwar[0] || !$fwar[0] && !$rmagic && $fwar[12] != "магией") {
// попадание
        if (rand(0, 100) <= $fwar[0]) {
// урон
            $damage = round(rand($fwar[1], $fwar[2]));
// уклон
            if (rand(0, 100) > $uklon) {
// щит
                if ($parring && $shield) if (rand(0, 100) <= $parring) {
                    if (!$rmagic && $fwar[12] != "магией" && $fwar[12] != "молнией") {
                        $damage -= $shield;
                        $t1 = " (щит " . $shield . ")";
                    } else {
                        $resist = round($damage * $shield / 100);
                        if ($resist) $tsh = rand(0, $resist); else $tsh = 0;
                        $damage -= $tsh;
                        $t1 = " (сопр. магии " . $tsh . ")";
                    }
                }
// броня
                if (!$rmagic && $fwar[12] != "магией" && $twar[5] && $fwar[12] != "молнией" && $twar[5]) $damage -= round(rand(0, $twar[5])); // armor
                if ($damage < 0) $damage = 0;
                if ($fwar[4]) $skrit = 5; else if ($rmagic || $fwar[12] == "магией" || $fwar[12] == "молнией") $skrit = 1; else $skrit = 2;
                if ($damage && rand(0, 100) < $skrit) {
                    $damage *= 2;
                    $tkrit = " критически";
                } else $tkrit = "";
                if ($location_items[$loct][$to]["god"]) $damage = 0;    // БОГ
// урон
                $tchar[1] -= $damage;
                $tchar[5] = time();
                if ($tchar[1] < 0) $tchar[1] = 0;
                if (!$answer && !$rmagic) {
                    add_journal($location, $from, "вы" . $ptitle . $tkrit . " " . $fwar[12] . " " . $damage . $t1 . $t2, "", "", ", ");
                    add_journal($location, "all", $fchar[0] . $ptitle . $tkrit . " " . $fwar[12] . " " . $damage . $t1 . $t2, $from, "", ", ");
                } else {
                    add_journal($location, $from, "Вы" . $ptitle . " по " . $tchar[0] . $tkrit . " " . $fwar[12] . " " . $damage . $t1 . $t2);
                    add_journal($location, $to, $fchar[0] . $ptitle . " по вам" . $tkrit . " " . $fwar[12] . " " . $damage . $t1 . $t2);
                    add_journal($location, "all", $fchar[0] . $ptitle . " по " . $tchar[0] . $tkrit . " " . $fwar[12] . " " . $damage . $t1 . $t2, $from, $to);
                }

// жена/муж
                if (substr($to, 0, 2) == "u." && $tchar[13] && $tchar[1] < $tchar[2]) {
                    $tm = explode(":", $tchar[13]);
                    if (time() > $tm[1] && file_exists("online/" . $tm[0]) && filesize("online/" . $tm[0]) != 1) {
                        $tmf = file("online/" . $tm[0]);
                        $tmf = trim($tmf[0]);
                        if ($tmf != $location) {
                            load_loc($tmf);
                            $tup = explode("|", $location_items[$tmf][$tm[0]]["user"]);
                            if (strpos($aloct[0], "*") !== false)
                                $aloct[0] = substr($aloct[0], 0, strpos($aloct[0], "*"));
                            if ($tup[2] == "m")
                                $ts = "Ваша жена (" . $aloct[0] . ") ранена!";
                            else
                                $ts = "Ваш муж (" . $aloct[0] . ") ранен!";
                            add_journal($tmf, $tm[0], "<a href=\"$PHP_SELF?sid=" . $tm[0] . "&p=" . $tup[0] . "&stele=1\">" . $ts . "</a>");
                            $tm[1] = time() + 300;
                            $tchar[13] = implode(":", $tm);
                        }
                    }
                }
// если убили, добавим труп
                if ($tchar[1] < 1) {
                    include "f_kill.dat";
                }
                else
                    $location_items[$loct][$to]["char"] = implode("|", $tchar); // иначе сохраним в f_kill.dat

            } else {
                if (!$answer) {
                    if (!$rmagic && $fwar[12] != "магией" && $fwar[12] != "молнией") {
                        add_journal($loct, $from, "вы" . $ptitle . " мимо (уклон)" . $t2, "", "", ", ");
                        add_journal($loct, "all", $fchar[0] . $ptitle . " мимо (уклон)" . $t2, $from, "", ", ");
                    } else {
                        add_journal($loct, $from, "вы" . $ptitle . " мимо (уклон от магии)" . $t2, "", "", ", ");
                        add_journal($loct, "all", $fchar[0] . $ptitle . " мимо (уклон от магии)" . $t2, $from, "", ", ");
                    }
                } else {
                    if (!$rmagic && $fwar[12] != "магией" && $fwar[12] != "молнией") {
                        add_journal($loct, $from, "Вы" . $ptitle . " по " . $tchar[0] . " мимо (уклон)" . $t2);
                        add_journal($loct, $to, $fchar[0] . " по вам мимо (уклон)");
                        add_journal($loct, "all", $fchar[0] . $ptitle . " по " . $tchar[0] . " мимо (уклон)" . $t2, $from, $to);
                    } else {
                        add_journal($loct, $from, "Вы" . $ptitle . " по " . $tchar[0] . " мимо (уклон от магии)" . $t2);
                        add_journal($loct, $to, $fchar[0] . $ptitle . " по вам мимо (уклон от магии)" . $t2);
                        add_journal($loct, "all", $fchar[0] . $ptitle . " по " . $tchar[0] . " мимо (уклон от магии)" . $t2, $from, $to);
                    }
                }
            }
        } else {
            if (!$answer && !$rmagic) {
                add_journal($location, $from, "вы" . $ptitle . " мимо" . $t2, "", "", ", ");
                add_journal($location, "all", $fchar[0] . $ptitle . " мимо" . $t2, $from, "", ", ");
            } else {
                add_journal($location, $from, "Вы" . $ptitle . " по " . $tchar[0] . " мимо" . $t2);
                add_journal($location, $to, $fchar[0] . $ptitle . " по вам мимо" . $t2);
                add_journal($location, "all", $fchar[0] . $ptitle . " по " . $tchar[0] . " мимо" . $t2, $from, $to);
            }
        }
    }// заклинание сорвалось

// если npc свободен, то атакует
    if (isset($location_items[$location][$from]) && ($answer || $rmagic)) {
        $fchar[7] = $to;
        $location_items[$location][$from]["char"] = implode("|", $fchar);
    }
    if (isset($location_items[$location][$from]) &&
        isset($location_items[$loct][$to]) &&
        $from != $to &&
        ($fwar[0] || !$fwar[0] && !$rmagic && $fwar[12] != "магией" && $fwar[12] != "молнией")
    ) {
        if (substr($to, 0, 2) == "n." && !$tchar[7]) {
            $tchar[7] = $from;
            $location_items[$loct][$to]["char"] = implode("|", $tchar);
        }
        if ($answer)
            attack($loct, $to, $from, 0, 0);
    }
}

function rndname() { return ""; }

/**
 * @param array $user
 * @return bool
 */
function is_female($user) {
    return strpos($user["user"], "|f|") !== false;
}

/** Перемещение НПС(?)
 * @param string $id индификатор
 * @param string $from откуда
 * @param string $to куда
 * @param int $gal флаг перемещения галопом
 * @param int $hide флаг скрытного перемещения
 */
function add_npc($id, $from = "", $to = "", $gal = 0, $hide = 0)
{
    global $location_items, $location, $login, $page_d, $loc_tt, $g_j2go;

    if ($from == $to)
        return;
    load_loc($from);
    load_loc($to);
    if ($from && $to && (!isset($location_items[$from]) || !isset($location_items[$to])))
        return;
    $ars = ["Появился", "исчез", "Пришел", "ушел", "прискакал", "поскакал", "пронесся"];
    if (is_user($id) && (
            is_female($location_items[$from][$id]) ||
            is_female($location_items[$to][$id])
        )
    )
        $ars = ["Появилась", "исчезла", "Пришла", "ушла", "прискакала", "поскакала",
            "пронеслась"];
    $tnpc = "";
    if ($from && isset($location_items[$from][$id])) {
        $floc = decode($loc_tt[$from], "d");
        $tnpc = $location_items[$from][$id];
        $tchar = get_name($tnpc);
        if (!$hide)
            if ($to && array_search($to, $floc)) {
                if ($gal && $gal != 1)
                    add_journal($from, "all", $tchar . " " . $ars[5] . " галопом " . $gal, $id);
                else
                    if (!$gal)
                        add_journal($from, "all",
                            $tchar . " " . $ars[3] . " " . $floc[array_search($to, $floc) - 1],
                            $id);
            } else
                add_journal($from, "all", $tchar . " " . $ars[1], $id);
        unset($location_items[$from][$id]);
    }
    if ($to && isset($location_items[$to])) {
        if (!$tnpc && isset($location_items[$to][$id])) {
            $tnpc = $location_items[$to][$id];
            $tchar = get_name($tnpc);
        }
        if ($tnpc) {
            $tloc = decode($loc_tt[$to], "d");
            if ($from && array_search($from, $tloc)) {
                if ($gal && $gal != 1)
                    add_journal($to, "all", $tchar . " " . $ars[6] . " галопом " . $gal, $id);
                else
                    if ($gal == 1)
                        add_journal($to, "all", $tchar . " " . $ars[4] . " галопом", $id);
                    else
                        add_journal($to, "all", $ars[2] . " " . $tchar, $id);
                if (is_npc($id)) { // история следов npc
                    $tchar = decode($tnpc ,"char");
                    $steps = explode(":", $tchar[12]);
                    if (count($steps) == 0)
                        $steps[] = $from;
                    else {
                        if ($steps[count($steps) - 1] == $to)
                            unset($steps[count($steps) - 1]);
                        else
                            $steps[] = $from;
                    }
                    $tchar[12] = implode(":", $steps);
                    $tnpc["char"] = implode("|", $tchar);
                }
            } else
                add_journal($to, "all", $ars[0] . " " . $tchar, $id);
            $location_items[$to][$id] = $tnpc;
            if ($from && is_user($id)) {
                if ($floc[1] == 1 && $tloc[1] != 1)
                    add_journal($to, $id, "Вы покинули охраняемую территорию");
                if ($floc[1] != 1 && $tloc[1] == 1)
                    add_journal($to, $id, "Вы на охраняемой территории");
            }
        }
    }
    if ($id == $login && $to && isset($location_items[$to][$id])) {
        $location = $to;
        if ($g_j2go)
            $page_d = 1;
    }
}

/**
 * @param array $tnpc
 * @return string
 */
function get_name($tnpc)
{
    return substr($tnpc["char"], 0, strpos($tnpc["char"], "|"));
}

/**"ИИ". Обновление состояния в локации
 * @param string $location_id локация
 */
function do_ai($location_id)
{
    global $game, $location, $location_items, $location_timers, $loc_tt, $g_logout,
           $login;
    $g_regen = 30;

    $location_ai = explode("|", $loc_tt[$location_id]["d"]);

    list($location_timers, $location_items) = run_timers($location_id, $location_timers, $location_items);

    $coord = explode("x", $location_id);

    list($guard, $users, $crim) = get_users($location_id, $location_items, $location_ai, $coord);

    if ($location_ai[1] == 1 && count($crim) > 0 && !$guard){
        // добавляем 1 стража
        $location_items = add_guard($location_id, $location_items);
    }

    // по всем объектам
    if ($location_items[$location_id]) {
        foreach ($location_items[$location_id] as $timer_id => $timer_v) {
            if (isset($location_items[$location_id][$timer_id])) {
                if (is_item($timer_id)) {
                    // удалить флаг, если он не в этой локе
                    if ($timer_id == "i.flag" && $game["floc"] != $location_id) {
                        unset($location_items[$location_id][$timer_id]);
                        continue;
                    }
                    if (is_castle($location_id) && substr($timer_id, 0, 4) != "i.s.")
                        continue;
                    $tmp = decode($location_items[$location_id], $timer_id);
                    if ($tmp[2] && time() > $tmp[2])
                        unset($location_items[$location_id][$timer_id]);
                    continue;
                }
                if (is_user($timer_id) || is_npc($timer_id)) {
                    $char = regenerate($location_id, $location_items, $timer_id, $g_regen);
                    if (is_user($timer_id)) {
                        if ($char[Char::crim_name] && time() > $char[Char::crim_time]) {
                            // сброс криминала
                            $char[Char::crim_name] = 0;
                            $char[Char::crim_time] = "";
                        }
                        if ($timer_id == $login)
                            $char[Char::sale_timer] = time();
                        if ($char[Char::sale_timer] &&
                            time() > $char[Char::sale_timer] + $g_logout * 5 &&
                            !file_exists("online/" . $timer_id)
                        ) {
                            unset($location_items[$location_id][$timer_id]);
                            continue;
                        }
                    }
                    if (is_npc($timer_id)) {
                        if (
                            $location == $location_id &&
                            time() > $char[Char::atk_timer] &&
                            $char[Char::hp_current] < $char[Char::hp_total] / 4 &&
                            rand(0, 100) < 50 &&
                            !in_array(substr($timer_id, 0, 4), ['n.s.', 'n.o.','n.z.'])
                        ) {
                            // убегаем
                            $b = 0;
                            $k = get_rnd_jmp($location_ai);
                            $loc1 = explode("|", $loc_tt[$k]["d"]);
                            if ($location_ai[1] == $loc1[1]) {
                                add_journal($location, "all", $char[Char::name] . " убегает");
                                if ($char[10]) {
                                    $move = explode(":", $char[10]);
                                    $move[3] = time() + rand($move[1], $move[2]);
                                    $char[10] = implode(":", $move);
                                }
                                $char[7] = "";
                                $location_items[$location_id][$timer_id]["char"] = implode("|", $char);
                                add_npc($timer_id, $location_id, $k);
                                $b = 1;
                            }
                            if ($b)
                                continue;
                        }
                        // Призраков не преследуем
                        if (
                            get_target($char) &&
                            isset($location_items[$location_id][$char[Char::atk_target]]) &&
                            is_user($char[Char::atk_target])
                        ) {
                            $tc = get_char($location_items, $location_id, get_target($char));
                            if ($tc[Char::ghost_flag])
                                $char[Char::atk_target] = "";
                        }
                        // жар-птица убегает от игроков
                        if (is_firebird($timer_id)) {
                            $b = 0;
                            foreach ($location_items[$location_id] as $key => $v)
                                if (is_user($key)) {
                                    add_npc($timer_id, $location_id, get_rnd_jmp($location_ai));
                                    $b = 1;
                                    break;
                                }
                            if ($b)
                                continue;
                        }
                        // обновить гвардов
                        if (is_guard($timer_id) && time() > $char[11]) {
                            add_npc($timer_id, $location_id, "");
                            continue;
                        }
                        if (isset($location_items[$location_id][$timer_id]["owner"])) {
                            // обработка подчиненных npc
                            $owner = decode($location_items[$location_id][$timer_id], "owner");
                            // хозяин крима крим
                            if ($char[Char::crim_name] && isset($location_items[$location_id][$owner[0]]))
                                docrim($location_id, $owner[0]);
                            if (!isset($owner[0])) $owner[0] = "";
                            if (!isset($owner[1])) $owner[1] = "";
                            if (!isset($owner[2])) $owner[2] = "";
                            if (!isset($owner[3])) $owner[3] = "";
                            if (!isset($owner[4])) $owner[4] = "";
                            if (!isset($owner[5])) $owner[5] = time() + 60 * 60;

                            $b = 0;
                            // вышло время
                            if ($owner[3] && time() > $owner[3] || time() > $owner[5] && !is_castle($location_id)) {
                                $b = 1;
                                unset($location_items[$location_id][$timer_id]["owner"]);
                                add_journal($location, $owner[0], $char[Char::name] . " покинул вас");
                                if ($owner[6])
                                    add_npc($timer_id, $location_id, $owner[6]);
                                else {
                                    $ttw = decode($location_items[$location_id][$timer_id],"war");
                                    if ($ttw[15]) {
                                        $ttwr = explode(":", $ttw[15]);
                                        add_npc($timer_id, $location_id, $ttwr[0]);
                                    }
                                    else
                                        add_npc($timer_id, $location_id);
                                }
                            }
                            // heal
                            if (substr($timer_id, 0, 5) == "n.he." &&
                                time() > $location_items[$location][$timer_id]["h_t"] &&
                                isset($location_items[$location][$owner[0]])) {
                                $tc = get_char($location_items,$location, $owner[0]);
                                if ($tc[1] < $tc[2]) {
                                    add_journal($location, "all", get_name($location_items[$location][$timer_id]) . ": " . $location_items[$location][$timer_id]["h_s"]);
                                    $htmp = rand($location_items[$location][$timer_id]["h_v1"], $location_items[$location][$timer_id]["h_v2"]);
                                    $tc[1] += $htmp;
                                    add_journal($location, $owner[0], $tc[0] . ": жизнь +" . $htmp);
                                    if ($tc[1] > $tc[2])
                                        $tc[1] = $tc[2];
                                    $location_items[$location][$owner[0]]["char"] = implode("|", $tc);
                                    $location_items[$location][$timer_id]["h_t"] = time() + $location_items[$location][$timer_id]["h_p"];
                                }
                            }
                            if (!$b) {
                                // следуем
                                if ($owner[1] && !isset($location_items[$location_id][$owner[1]]))
                                    for ($k = 3; $k < count($location_ai); $k += 2)
                                        if (isset($location_items[$location_ai[$k]][$owner[1]])) {
                                            $bc = 1;
                                            if (is_mercenary($timer_id)) {
                                                if (is_castle($location_id) &&
                                                    !is_castle_in($location_id))
                                                    $bc = 0;
                                                if ($bc && !is_castle_in($k)) {
                                                    $count = 0;
                                                    if ($location_items[$location_ai[$k]])
                                                        foreach (array_keys($location_items[$location_ai[$k]]) as $m)
                                                            if (is_mercenary($m))
                                                                $count++;
                                                    if ($count >= 5) {
                                                        $bc = 0;
                                                        add_journal($location_ai[$k],
                                                            $owner[0],
                                                            $char[Char::name] . " говорит: я туда не пойду, там и так полно стражников");
                                                    }
                                                }
                                            }
                                            if ($bc && is_user($owner[1])) {
                                                $tc = get_char($location_items, $location_ai[$k], $owner[1]);
                                                if ($tc[Char::ghost_flag])
                                                    $bc = 0;
                                            }
                                            if ($bc && !is_mercenary($timer_id)) {
                                                $count = 0;
                                                foreach ($location_items[$location_ai[$k]] as $jn) {
                                                    if (isset($jn["owner"]) && strpos($jn["owner"], $owner[1]) !== false) {
                                                        $count++;
                                                        if ($count > 3) {
                                                            $bc = 0;
                                                            break;
                                                        }
                                                    } //не следуют если больше трех
                                                }
                                            }
                                            if ($bc) {
                                                $char[Char::atk_target] = "";
                                                $location_items[$location_id][$timer_id]["char"] = implode("|", $char);
                                                add_npc($timer_id, $location_id, $location_ai[$k]);
                                                $char = get_char($location_items, $location_ai[$k], $timer_id);
                                                $char[Char::horse_flag] = "";
                                                $location_items[$location_ai[$k]][$timer_id]["char"] = implode("|", $char);
                                                $owner[5] = time() + 60 * 60;    // 1 час ждет движения
                                                $location_items[$location_ai[$k]][$timer_id]["owner"] = implode("|", $owner);
                                                $b = 1;
                                                break;
                                            }
                                        }
                                if (!$b) {        //$timer_id не ушел
                                    // охраняем
                                    if (!is_mercenary($timer_id) && $owner[2] && isset($location_items[$location_id][$owner[2]])) {
                                        $k1 = array_keys($location_items[$location_id]);
                                        foreach ($k1 as $k)
                                            if ($k != $timer_id && $k != $owner[2] && $k != $owner[0] && $k != $owner[1]) {
                                            $ch = get_char($location_items, $location_id, $k);
                                            if ($ch[Char::atk_target] == $owner[2] ||
                                                substr($location_items[$location][$ch[Char::atk_target]]["owner"],
                                                    0, strlen($owner[2])
                                                ) == $owner[2]
                                            ) {
                                                if ($location_ai[1] == 1) {
                                                    $tco = get_char($location_items, $location, $owner[2]);
                                                    if ($tco[Char::crim_name])
                                                        break;
                                                }
                                                $char[Char::atk_target] = $k;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            if ($b)
                                continue;
                        } else {
                            $owner[1] = "";
                        }
                        if (get_target($char) &&
                            !$owner[1] &&
                            !isset($location_items[$location_id][get_target($char)])) {
                            $b = 0;
                            if (!is_mercenary($timer_id) && !(is_firebird($timer_id))){
                                $tfound = 0;
                                for ($k = 3; $k < count($location_ai); $k += 2) {
                                    if (isset($location_items[$location_ai[$k]][$char[Char::atk_target]])) {
                                        $tfound = 1;
                                        $loc1 = decode($loc_tt[$location_ai[$k]], "d");
                                        if ($location_ai[1] == $loc1[1] || is_guard($timer_id))
                                            $b = 1;
                                        // hiding от гардов не действует
                                        if (is_user($char[Char::atk_target]) && !is_guard($timer_id)) {
                                            $skills = get_skills($location_items, $location_ai[$k], $char[Char::atk_target]);
                                            if (rand(0, 100) <= ($skills[17] * 4 + $skills[1])) {
                                                $b = 0;
                                                add_journal($location_ai[$k], $char[Char::atk_target], "Вы скрылись от погони");
                                            }
                                        }
                                        // призраков не преследуем
                                        if (is_user($char[Char::atk_target])) {
                                            $tc = get_char($location_items, $location_ai[$k], $char[Char::atk_target]);
                                            if ($tc[Char::ghost_flag]) {
                                                $char[Char::atk_target] = "";
                                                $location_items[$location_id][$timer_id]["char"] = implode("|", $char);
                                                $b = 0;
                                            }
                                        }

                                        if ($b) {    // погоня
                                            $location_items[$location_id][$timer_id]["char"] = implode("|", $char);
                                            add_npc($timer_id, $location_id, $location_ai[$k]);
                                        } else
                                            $tfound = 0;
                                        break;
                                    }
                                }
                                if (!$tfound) {
                                    $char[Char::atk_target] = "";
                                    $location_items[$location_id][$timer_id]["char"] = implode("|", $char);
                                }
                            }
                            if ($b)
                                continue;
                            else
                                $char[Char::atk_target] = "";
                        }
                        if (!get_target($char)) {
                            // гварды, пираты и тамплиеры атакуют кримов
                            if (count($crim) > 0 && (
                                    is_guard($timer_id) ||
                                    is_tampler_npc($timer_id) ||
                                    is_pirate_npc($timer_id))
                            ) {
                                $char[Char::atk_target] = get_random($crim);
                            }
                            // кримы атакуют "честных" игроков
                            if (($char[Char::crim_name] || is_crim_npc($timer_id)) && count($users) > 0) {
                                $char[Char::atk_target] = get_random($users);
                            }
                        }
                        if (
                            is_mercenary($timer_id) &&
                            is_castle($location_id) &&
                            !is_castle_in($location_id) &&
                            (!get_target($char) || !isset($location_items[$location_id][get_target($char)]))
                        ) {
                            // охрана замка
                            global $lcen;
                            if (gettype($lcen) != "array") {
                                $lcen = array();
                                $gate = substr($location_id, 0, 4) . "gate";
                                load_loc($gate);
                                $d = decode($loc_tt[$gate], "d");
                                if (strpos($d[0], "*") !== false) {
                                    $clanc = substr($d[0], strpos($d[0], "*") + 1, strrpos($d[0], "*") - strpos($d[0], "*") - 1);
                                    foreach ($location_items[$location_id] as $k => $v)
                                        if (is_user($k) &&
                                            strpos($location_items[$location_id][$k]["char"], "*" . $clanc . "*") === false &&
                                            strpos($d[0], ":" . $k . ":") === false) {
                                        $tct = get_char($location_items, $location_id, $k);
                                        if (!$tct[Char::ghost_flag])
                                            $lcen[] = $k;
                                    }
                                }
                            }
                            if (count($lcen) > 0)
                                $char[Char::atk_target] = get_random($lcen);
                        }
                        if (!get_target($char) &&
                            !$owner[1] &&
                            ($char[10] || (!$char[10] && $char[12])) &&
                            !is_mercenary($timer_id)
                        ) {
                            // случайное движение
                            $move = 0;
                            $steps = 0;
                            if ($char[10]) $move = explode(":", $char[10]);
                            if ($char[12]) $steps = explode(":", $char[12]);
                            $b = 0;
                            if (!$char[10] && $char[12]) {
                                $location_items[$location_id][$timer_id]["char"] = implode("|", $char);
                                $lt = $steps[count($steps) - 1];
                                $blt = 1;
                                if (is_firebird($timer_id)) {
                                    load_loc($lt);
                                    if (count($location_items[$lt]) != 0) $blt = 0;
                                }
                                if ($blt) {
                                    add_npc($timer_id, $location_id, $lt);
                                    $b = 1;
                                }
                            } else if (time() > $move[3]) {
                                if ($char[12] && count($steps) >= $move[0]) {
                                    $b = 1;
                                    $k = $steps[count($steps) - 1];
                                } else {
                                    $b = 0;
                                    $k = get_rnd_jmp($location_ai);
                                }
                                if (!isset($loc_tt[$k]["d"]))
                                    load_loc($k);
                                $loc1 = decode($loc_tt[$k], "d");
                                if ($location_ai[1] == $loc1[1] || $b) {
                                    $move[3] = time() + rand($move[1], $move[2]);    // след. ход
                                    $char[10] = implode(":", $move);
                                    $location_items[$location_id][$timer_id]["char"] = implode("|", $char);
                                    $blt = 1;
                                    if (is_firebird($timer_id)) {
                                        load_loc($k);
                                        if (count($location_items[$k]) != 0) $blt = 0;
                                    }
                                    if ($blt) {
                                        add_npc($timer_id, $location_id, $k);
                                        $b = 1;
                                    }
                                }
                            }
                            if ($b)
                                continue;
                        }
                    }
                    $location_items[$location_id][$timer_id]["char"] = implode("|", $char);
                    if (get_target($char) && !is_user($timer_id))
                        attack($location_id, $timer_id, get_target($char));
                }
                else {
                    unset($location_items[$location_id][$timer_id]);
                    continue;
                }
            }
        }
    }
}

/**
 * @param string $name
 * @return bool
 */
function is_firebird($name)
{
    return $name == "n.a.b.jarpt.1";
}

/**
 * @param string $location_id
 * @param array $location_items
 * @param string $timer_id
 * @param int $g_regen
 * @return array
 */
function regenerate($location_id, $location_items, $timer_id, $g_regen)
{
    $char = get_char($location_id, $location_items, $timer_id);
    $tm = time() - $char[Char::regen_timer];
    if ($tm > $g_regen && (
            $char[Char::hp_current] != $char[Char::hp_total] ||
            $char[Char::mp_current] != $char[Char::mp_total]
        ) && (
            is_npc($timer_id) ||
            (is_user($timer_id) && !$char[Char::ghost_flag]))
    ) {
        // регенерация ХП/МП
        if (is_user($timer_id))
            $skills = get_skills($location_id, $location_items, $timer_id);
        else {
            $skills[5] = 0;
            $skills[16] = 0;
        }
        $char[Char::hp_current] = min(
            $char[Char::hp_current] += round($tm / ($g_regen - $skills[16] * 4)),
            $char[Char::hp_total]
        );
        $char[Char::mp_current] = min(
            $char[Char::mp_current] += round($tm / ($g_regen - $skills[5] * 4)),
            $char[Char::mp_total]
        );
        $char[Char::regen_timer] = time();
        return array($char, $skills);
    }
    return $char;
}

/**
 * @param string $location_id
 * @param array $location_items
 * @param array $location_ai
 * @param array $coord
 * @return array
 */
function get_users($location_id, $location_items, $location_ai, $coord)
{
    $crim = [];
    $users = [];
    $guard = 0;
    if ($location_items[$location_id]) {
        foreach ($location_items[$location_id] as $timer_id => $timer_v) {
            if ($timer_id != "u.qv") {
                if (is_user($timer_id)) {
                    $uc = get_char($location_id, $location_items, $timer_id);
                    if (!$uc[8]) {
                        $us = get_skills($location_id, $location_items, $timer_id);
                        if (rand(0, 100) > $us[17] * 6)
                            $users[] = $timer_id;
                        if (
                            ($location_ai[1] != 3 && $uc[9]) ||
                            $coord[2] >= 1099 && (
                                ($location_ai[1] == 2 && $uc[14] == "p") ||
                                ($location_ai[1] == 3 && $uc[14] == "t")
                            )
                        ) {
                            $crim[] = $timer_id;
                        }
                    }
                }
                if (is_crim_npc($timer_id))
                    $crim[] = $timer_id;
                if (is_guard($timer_id))
                    $guard = 1;
            }
        }
    }
    return [$guard, $users, $crim];
}

/**
 * @param string $location_id
 * @param array $location_timers
 * @param array $location_items
 * @return array
 * @throws Exception
 */
function run_timers($location_id, $location_timers, $location_items)
{
// таймеры
    if (isset($location_timers[$location_id])) {
        foreach ($location_timers[$location_id] as $timer_id => $timer_v) {
            if (time() > $timer_id) {
                if (gettype($location_timers[$location_id][$timer_id]) == "array" ||
                    is_npc($location_timers[$location_id][$timer_id])
                ) {
                    //  загружаем в $npc из папки npc	id|resp_min:resp_max|move_num:time_min:time_max
                    if (gettype($location_timers[$location_id][$timer_id]) == "array") {
                        $npc = $location_timers[$location_id][$timer_id];
                        $npc_id = $npc["id"];
                        unset($npc["id"]);
                    } else {
                        $ta = decode($location_timers[$location_id], $timer_id);
                        $npc_id = $ta[0];
                        if (is_crim_npc($npc_id) || is_animal($npc_id))
                            $npc_id = substr($npc_id, 0, strrpos($npc_id, "."));
                        if (!file_exists("npc/" . $npc_id)) {
                            unset($location_timers[$location_id][$timer_id]);
                            throw new \Exception("Cant load npc $npc_id");
                        }//("err: no npc/".$npc_id);
                        $npc = load_npc($npc_id);
                        $npc_id = $ta[0];
                        $twar = decode($npc, "war");
                        $twar[15] = $location_id . ":" . $ta[1];
                        $npc["war"] = implode("|", $twar);
                        if ($ta[2]) {
                            $tchar = decode($npc, "char");
                            $tchar[10] = $ta[2];
                            $npc["char"] = implode("|", $tchar);
                        }
                    }

                    // случ. предметы
                    if (isset($npc["itemsrnd"])) {
                        $items_rand = explode("|", $npc["itemsrnd"]);
                        foreach (array_keys($items_rand) as $key) {
                            if ($items_rand[$key]) {
                                $trnd = explode(":", $items_rand[$key]);
                                $trndc = round(rand($trnd[2], $trnd[3]));
                                if (rand(0, 100) <= $trnd[1] && $trndc > 0) {
                                    if ($npc["items"]) {
                                        $npc["items"] .= "|" . $trnd[0] . ":" . $trndc;
                                    } else {
                                        $npc["items"] = $trnd[0] . ":" . $trndc;
                                    }
                                }
                            }
                        }
                        unset($npc["itemsrnd"]);
                    }

                    // респавн текущий
                    $location_items[$location_id][$npc_id] = $npc;
                    unset($location_timers[$location_id][$timer_id]);
                    continue;
                }
                if (is_item($location_timers[$location_id][$timer_id])) {
                    $tmp = decode($location_timers[$location_id], $timer_id);
                    $item = explode("|", file_get_contents("items/" . $tmp[0]));
                    $tc = rand($tmp[1], $tmp[2]);
                    if ($tc > 0)
                        $location_items[$location_id][$tmp[0]] = $item[0] . "|" . $tc . "|0";
                    else
                        unset($location_items[$location_id][$tmp[0]]);
                    addtimer($location_id, $timer_id, rand($tmp[3], $tmp[4]), $location_timers[$location_id][$timer_id], 1);
                    continue;
                }
                // Использование не найдено
                $loct = $location_id;
                $curr = $timer_id;
                throw new \Exception('Dead code? : eval($location_timers[$loct][$curr])'.
                    "\n($location_timers[$loct][$curr])");
            }
        }
    }
    return [$location_timers, $location_items];
}

/**Добавление/обновление таймера
 * @param string $loct локация
 * @param int $curr текущий таймер
 * @param int $time период
 * @param string $text текст для установки или old для использования старого
 * @param int $delete флаг удаления текущего
 */
function addtimer($loct, $curr, $time, $text = 'old', $delete = 1)
{
    //$curr - тек. таймер в локации $loct
    global $location_timers;
    load_loc($loct);
    $new = time() + $time;
    while (isset($location_timers[$loct][$new]))
        $new++;
    if ($text == 'old')
        $text = $location_timers[$loct][$curr];
    $location_timers[$loct][$new] = $text;
    if ($delete && $curr)
        unset($location_timers[$loct][$curr]);
}

/**
 * @param array $location_list
 * @return mixed
 */
function get_rnd_jmp($location_list)
{
    return $location_list[2 + 2 * rand(0, (count($location_list) - 2) / 2 - 1) + 1];
}

/**
 * @param array $arr
 * @return mixed
 */
function get_random($arr)
{
    return $arr[rand(0, count($arr) - 1)];
}

/**
 * @param string $name
 * @return bool
 */
function is_mercenary($name)
{
    return substr($name, 0, 4) == 'n.o.';
}

/**
 * @param $name
 * @return bool
 */
function is_animal($name)
{
    return substr($name, 0, 4) == 'n.a.';
}

/**
 * @param $npc_id
 * @return bool
 */
function is_crim_npc($npc_id)
{
    return substr($npc_id, 0, 4) == 'n.c.';
}

/**
 * @param string $location_id
 * @return bool
 */
function is_castle_in($location_id)
{
    return substr($location_id, 3) == '.in';
}

/**
 * @param string $name
 * @return bool
 */
function is_pirate_npc($name)
{
    return substr($name, 0, 4) == 'n.p.';
}

/**
 * @param string $name
 * @return bool
 */
function is_tampler_npc($name)
{
    return substr($name, 0, 4) == 'n.t.';
}

/**
 * @param string $name
 * @return bool
 */
function is_guard($name)
{
    return substr($name, 0, 4) == 'n.g.';
}

/**
 * @param string $location_id
 * @return bool
 */
function is_castle($location_id)
{
    return substr($location_id, 0, 2) == 'c.';
}

/**
 * @param array $arr
 * @param string $key
 * @param string $delim
 * @return array
 */
function decode($arr, $key, $delim = '|') {
    return explode($delim, $arr[$key]);
}

/**
 * @param string $location_id
 * @param array $location_items
 * @param string|int $timer_id
 * @return array
 */
function get_skills($location_id, $location_items, $timer_id)
{
    return decode($location_items[$location_id][$timer_id], 'skills');
}

/**
 * @param array $char
 * @return string
 */
function get_target($char)
{
    return $char[Char::atk_target];
}

/**
 * @param string $location_id
 * @param array $location_items
 * @param string|int $timer_id
 * @return array
 */
function get_char($location_id, $location_items, $timer_id)
{
    return decode($location_items[$location_id][$timer_id],'char');
}

/**
 * @param string $l_id
 * @param array $location_items
 * @return array
 */
function add_guard($l_id, $location_items)
{
    srand((float)microtime() * 10000000);
    $id = 'n.g.' . rand(5, 9999);
    $title = rndname() . ' [стража]';
    $location_items[$l_id][$id] = [
        'char' => $title . '|1000|1000|100|100|' . time() . '1||||||' . (time() + 600),
        'war' => "100|100|100|2|0|10|20|0|0|10|30|40|алебардой|0||",
        'items' => 'location_id.w.t.alebarda:1',
        'equip' => 'location_id.w.t.alebarda'
    ];
    add_journal($l_id, 'all', 'Появился ' . $title);
    return $location_items;
}

/**
 * @param string $name
 * @return bool
 */
function is_item($name)
{
    return substr($name, 0, 2) == 'i.';
}

/**
 * @param string $name
 * @return bool
 */
function is_npc($name)
{
    return substr($name, 0, 2) == 'n.';
}

/**
 * @param string $name
 * @return bool
 */
function is_user($name)
{
    return substr($name, 0, 2) == 'u.';
}

/**
 * @param string $npc_id
 * @return array
 */
function load_npc($npc_id) {
    $npc = [];
    include "npc/" . $npc_id;
    return $npc;
}
