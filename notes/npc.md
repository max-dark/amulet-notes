# Структура "NPC"

`$npc = include 'npc/'.$npc_id;`

NPC - это PHP-файл с ассоциативным массивом.

## Типы NPC

Тип NPC определяется по его ID:

* `n.a.*` - животные
    * `n.a.losh` - лошади
    * `n.a.b.*` - птицы
* `n.c.*` - монстры
* `n.m.*` - гномы
* `n.w.*` - "божественаая личность"
* `n.z.*` - зомби
* `n.s.*` - призван с помощью магии
* `n.g.*` - гварды, "городская стража"
* `n.h.*` - лекари, могут воскрешать призраков
* `n.he.*` - спутники-лекари, восстанавливают ХП хозяину(реализованы только "феи", нанимаются у `n.w.Mihail`)
* остальные считаются "людьми"

## Поля структуры

Есть у всех типов NPC:
* `char` - имя/название и состояние
* `war` - боевые характеристики

Дополнительные:
* `bank` - у торговцев(?)
* `equip` - что надето
* `items` - инвентарь
* `itemsrnd` - случайные предметы в инвентаре при респе
* `osvej` - дроп с животных/монстров при разделке трупа

Для спутников-лекарей(`n.he.*`):
* `h_t` - время отката
* `h_p` - период отката
* `h_s` - "слова заклинания"
* `rand(h_v1, h_v2)` - отхил, минимальный и максимальный

Используемые движком
* `owner` - хозяин NPC и настрйки поведения
    * 0 - ID хозяина
    * 1 - ID за кем следуем
    * 2 - ID кого охраняем
    * 3 - время следования(пустое - "всегда")
    * 4 - флаг, удалить нпс, когда отпустили
    * 5 - время ожидания
    * 6 - ID локации для возврата
* `name` - назначенное хозяином имя для NPC
* `id` - ID NPC, используется для таймера респа
* `qv` - таймер, используется в диалогах

Для "квестовых" NPC(`n.q.*`):
* `nspeak` - пустой либо содержит сообщение(0) и ID необходимого предмета(1)
* `in` - сообщение при присоединении
* `out` - сообщение при уходе
* `ok` - локация назначения(0), сообщение при доставке(1), список предметов в награду при доставке(2)