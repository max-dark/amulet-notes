# Предметы item

`$it = explode("|", $item)`

Описания выдраны из

 - 1/f_lookitem.dat

##  i.* Предметы
### Общие характеристики предметов

 - `item_name  = $it[0];` // название
 - `item_price = $it[1];` // базовая цена(при торговле с нпс умножается на ставку этого нпс)

### i.a.* броня

 - `armor   = $it[2];` // добавка к защите
 - `lvl_min = $it[3];` // требования к характеристикам персонажа в формате str:dex:int

### i.w.* оружие

 - r.* стрелковое/метательное
 - k.* кинжалы
 - t.* топоры
 - s.* мечи
 - u.* ударное(молоты и прочее)

Параметры:
 - `dmg_min = $it[2];` // урон минимальный
 - `dmg_max = $it[3];` // урон максимальный
 - `lvl_min = $it[4];` // требования к характеристикам в формате str:dex:int
 - `atk_spd = $it[5];` // скорость атаки(количество секунд на 1 атаку)
 - `atk_msg = $it[6];` // чем бьет("мечом", "ножом" и тд)
 - `ammo    = $it[7];` // тип снаряда(только для стрелкового/метательного)

### i.f.* Пища восстанавливает здоровье и иногда ману

 - `hp = $it[2];` // добавка к ХП
 - `mp = $it[3];` // добавка к МП

### i.i.* Самоцвет, при инкрустировании добавляет магические свойства предметам.

 - `list = $it[2];` // список свойств самоцвета(разделитель - запятая).
    формат свойства - что_меняет(`x`):на_сколько(`y`).
    ```
    (x > 50) ? char[x - 50] += y : war[x] += y
    ```
 - `info = $it[3];` // словесное описание свойств

### Другие варианты

- i.b.* Бутылка с зельем, которую можно бросить под ноги цели, пузырек разобьется и элексир попадет на кожу.
- i.bc.* Цветы/букеты
- i.c.* Драг камни
- i.h.* Дроп с животных при освежевании. Материалы для ремесла(?)
    * k* - клыки
    * kog* - когти
    * kop* - копыта
    * p* - перья
    * r* - рога
    * s* - шкура
    * другое: bone - кость, jalo - жало
- i.key.* ключи
- i.m.* Свиток с заклинанием, исчезает после прочтения.
- i.ms* Свитки призыва
- i.med.* медальоны
- i.oj.* ожерелья
- i.q.* квестовые(?) предметы
- i.r.* Руна с заклинанием, после использования не исчезает.
- i.rr.* Руна для телепортации
- i.ring.* кольца
- i.s.* стационарные предметы(нужно уточнить)
- i.s.d.* трупы
    * 0 - имя/название
    * 1 - флаг, что сбор предметов не будет преступлением. 0 или 1
    * 2 - время исчезновения(время убийства + период, указанный в настройках)
    * 3 - список предметов. разделитель - `,`(запятая)
- i.set.* наборы/инструменты для ремесла

## Магия(m.*)

0 - название  
1 - затраты маны  
2 - уровень  
3 - "заклинание"  
4-5 - урон или отхил мин-макс  
6 - флаг "нужна цель"  
7 - флаг "действует только на преступников"  
8 - скорость  
9 - период  
10 - описание  
m.* заклинания  
m.heal* отхил  
m.s.* заклинания призыва  
m.w.* боевые заклинания  

## Приемы(p.*)

0 - название, 2 - описание  
p. боевые 1 - откат  
p.d. защитные 1 - период действия  