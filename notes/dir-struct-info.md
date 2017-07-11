# Структура директорий

* `/game/` - исходники и данные
    * `data/clans/` - список кланов
    * `data/all.dat` - счетчик пользователей
    * `data/game.dat` состояние переменной `$game`.  
        Является глобальным мьютексом, блокирующим обновление файлов - только один пользователь имеет доступ
    * `desc/` описания умений
    * `items/` - описание предметов(см. item-info.md)
    * `loc_f/` - описание локации
    * `loc_t/` - состояние локациий "по умолчанию"
    * `loc_i/` - текущее состояние локаций
    * `npc/` - состояние НПС "по умолчанию"
    * `online/` - список игроков "в игре"
    * `plugin/` - модули игры
    * `speak/` - диалоги
    * `f_*.inc` - модули с функциями
    * `config.php` - настройки
    * `index.php` - "запускатор"
* `src/` - переработанные исходники