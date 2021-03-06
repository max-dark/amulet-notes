# Структура данных "Пользователь"

Хранит данные о регистрации, настройки, предметы и характеристики персонажа

ID текущего пользователя записаны в глобальной переменной `$login`

Если пользователь уходит в "offline", то данные сохраняются в [базу данных](user-db.md)
и при входе восстанавливаются из нее.

Во время игровой сессии данные хранятся в текущей [локации](location.md)
```php
$currentUser = $loc_i[$loc][$login];
```

## Поля структуры:

* user - данные регистрации
* o - настройки отображения
* journal - журнал событий
* char - состояние персонажа: ХП/МП, таймеры и т.д. [Описание](user-char.md)
* items - список предметов(надетых и в рюкзаке)
* equip - Что надето(броня/оружие)
* bank - список предметов в хранилище
* skills - экспа, уровень персонажа и умений. [Список](skill-list.md)
* war - характеристики персонажа. Зависит от skills и equip. [Описание](user-war.md)
* magic - список изученных заклинаний, для использоваия нужен предмет "книга заклинаний"
* priem - список изученных приемов
* macro - "макросы"
* macrol - последнее выполненное действие
* def - действующее защитное умение
* msg - почта, список контактов
* msgt - почта, список сообщений
* trade - для обмена с другими игроками
    * to - ID пользователя, с кем идет обмен
    * i - список предметов, предлагаемых игроком для обмена с "to"
    * ito - список предметов, предлагаемых "to" для обмена с игроком
    * a - флаг подтверждения обмена. Для успешного обмена обе стороны должны подтвердить обмен
* name - содержит назначенную игроком кличку лошади, если сели на нее верхом
* srv - время входа в игру
* time - время выхода из игры
* loc - ID локации, где была последняя активность игрока. Сохраняется при выходе и используется при входе.
* god - "режим б-га" для админов - игнорирует урон
* qvi - описание для "квестового" пользователя("u.qv")

Обязательными полями являются `user`, `char`, `skills` и `war`. Остальные дабавляются/удаляются "на лету".

## Данные регистрации(user)

`$u = explode("|", $loc_i[$loc][$login]["user"]);`

* тип: строка
* разделитель значений: `|`

```php
//    0     |1|2| 3|4|    5     |6|7|8|9|10
// "password| |m|33| |1289395943|0|0|0|0|0"
$u[0] // строка, пароль
$u[2] // строка, пол(`f` или `m`)
$u[3] // число, возраст(можно изменять в настройках)
$u[5] // число, дата/время регистрации
```
Остальное вроде бы не используется(пока что не нашел мест применения)

## Настройки отображения(o)

`$o = explode("|", $loc_i[$loc][$login]["o"]);`

* тип: строка
* разделитель значений: `|`
* Задают флаги для формирования игровых экранов
* Назначаются пользователем.
* При загрузке заменяют глобальные настройки.

```php
//   0|  1  |2|3|4|5|6| 7|8|9|10
// "30|15000|1|0|0|1|0|22|0|1|0"
$o[0] - $g_list   // число, Количество элементов списка на одной странице (3..30)
$o[1] - $g_size   // число, Размер страницы (700..15000)
$o[2] - $g_j2loc  // флаг, Сообщать о приходящих (1-вкл,0-выкл)
$o[3] - $g_j2go   // флаг, Отображение описания локаций при переходе (1-вкл,0-выкл)
$o[4] - $g_menu   // число, Тип меню.
$o[5] - $g_sounds // флаг, Определяет как показывается наличие пользователей/НПС в соседних локах.
$o[6] - $g_joff   // флаг, Отключить отображение журнала (1-да,0-нет).
$o[7] - $g_smenu  // строка, Дополнительные пункты в меню для быстрого доступа к предметам и умениям.
$o[8] - $g_map    // число, Отображение ссылки на карту и ее тип.
$o[9] - $g_smf    // флаг, Использовать маленький шрифт.
```

## Журнал событий(journal)

`$journal = $loc_i[$loc][$login]["journal"];`

* тип: строка
* макс размер - 800
* разделитель значений: `|`, заменяется на `<br/>` при выводе на экран
* содержит список последних событий.
* новое событие добавляется в конец списка

## TODO

добавить описание для остальных полей
