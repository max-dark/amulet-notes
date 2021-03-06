# Таймеры

## Предметы

* тип: строка
* разделитель: `|`

```php
$t = explode('|', $timer);
```
* `$itemId = $t[0]` - ID предмета
* `$count = rand($t[1], $t[2])` - случайное количество, минимальное и максимальное
* `$nextResp = rand($t[3], $t[4])` - время до следующего респа, минимальное и максимальное

Если `$count == 0`, то предмет удаляется из списка объектов.

Далее таймер переустанавливается на время `time() + $nextResp`

## NPC

Алгоритм при срабатывании таймера:
* получить ID NPC и его описание
* если у NPC есть ключ `itemsrnd`, то
    * выбрать случайным образом список предметов и их количество
    * добавить выбранные в `items`
    * удалить ключ `itemsrnd`
* Добавить NPC в список объектов
* удалить таймер.

Для NPC таймеры могут быть 2х видов:
* строка
* массив

Отличаются они способом получения ID NPC и его описания.

### в виде строки

разделитель: `|`

```php
$timer = explode('|', $loc_t[$locId][$timerId]);
$npcId = $timer[0];
$npc = include "npc/$npcId";
$npc['war'][15] = $locId  . ':' . $timer[1];
$npc['char'][10] = $timer[2]; // если не пустой
```

### в виде массива

Описание NPC копируется из таймера.

```php
$npc   = $timer;
$npcId = $npc['id'];
unset($npc['id']);
```

### Установка таймера для респа NPC

Таймер для респа NPC устанавливается при его смерти.

Настройки таймера берутся из `$npc['war'][15]`:
```php
$timer = explode(':', $npc['war'][15]);
```
где
* `$timer[0]` - локация, куда добавляется таймер(где респается NPC)
* `rand($timer[1], $timer[2])` - время до респа
* `$timer[3]` - флаг, определяет как добавляется таймер
    * установлен - как массив
        * `$npc['id'] = $npcId;`
        * восстанавливается ХП/МП
        * сбрасывается цель атаки
        * очищается `$npc[12]`
        * сбрасываются `items`, `equip` и `osvej`
    * сброшен - как строка в формате `$npcId|$timer[1]:$timer[2]`
        * если не пуст `$npc['char'][10]`, то добавляется к строке таймера
