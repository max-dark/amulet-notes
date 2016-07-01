`include 'npc/'.$npc_id;`

NPC - это PHP-файл с массивом $npc
Формат массива похож на описание игрока(смотри notes/location.md)

    <?php
    $npc = array(
        // notes/char-info.md
        'char' => 'гaлкa|3|3|1|1||||||||',
        // notes/war-info.md
        'war' => '60|0|1|3|||15||||||клювoм|1||',
        // Необязательные поля
        // дроп при разделке трупа osvej = item_type:count|...
        'osvej' => 'i.h.p.perya:6',
        // Случайный дроп  itemsrnd = item_type:chance:min_count:max_count|...
        'itemsrnd' => 'i.med.m:10:0:1|i.ring.b:10:0:1|i.ring.m:10:0:1|i.ring.l:10:0:1',
        'bank' => '',
        'items' => '',
        'equip' => ''
    );