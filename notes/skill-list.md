# Список атрибутов/умений(User::skills)

`$skills = explode("|", $loc_i[$loc][$login]["skills"]);`

"Атрибуты":
* `$skills[1]`: сила
* `$skills[1]`: ловкость
* `$skills[2]`: интелект
* `$skills[3]`: текущее значение exp
* `$skills[4]`: Доступные для распределения очки умений

```php
// список всех скиллов
$arr_skills = [
    "str"          =>  0, "dex"          =>  1, "int"          =>  2, "meditation"   =>  5,
    "steal"        =>  6, "animaltaming" =>  7, "hand"         =>  8, "coldweapon"   =>  9,
    "ranged"       => 10, "parring"      => 11, "uklon"        => 12, "magic"        => 13,
    "magic_resist" => 14, "magic_uklon"  => 15, "regeneration" => 16, "hiding"       => 17,
    "look"         => 18, "steallook"    => 19, "animallore"   => 20, "spirit"       => 21,
    "healing"      => 22, "alchemy"      => 23, "mine"         => 24, "smith"        => 25,
    "lumb"         => 26, "bow"          => 27, "stone"        => 28, "fish"         => 29,
    "food"         => 30, "necro"        => 31, "currier"      => 32,
];
// расшифровка
$arr_title = [
    "str"          => "Сила",          "dex"          => "Ловкость",
    "int"          => "Интеллект",     "meditation"   => "Медитация",
    "steal"        => "Кража",         "animaltaming" => "Прир.животных",
    "hand"         => "Рукопашная",    "coldweapon"   => "Холодн.оружие",
    "ranged"       => "Стрельба",      "parring"      => "Парирование",
    "uklon"        => "Уклон",         "magic"        => "Магия",
    "magic_resist" => "Сопр.магии",    "magic_uklon"  => "Уклон от магии",
    "regeneration" => "Регенерация",   "hiding"       => "Скрытность",
    "look"         => "Осторожность",  "steallook"    => "Подглядывание",
    "animallore"   => "Изуч.животных", "spirit"       => "Спиритизм",
    "healing"      => "Лечение",       "alchemy"      => "Алхимия",
    "mine"         => "Рудокоп",       "smith"        => "Кузнец",
    "lumb"         => "Лесоруб"        "bow"          => "Плотник",
    "stone"        => "Ювелир",        "fish"         => "Рыболов",
    "food"         => "Повар",         "necro"        => "Некромант",
    "currier"      => "Друид",
];
```