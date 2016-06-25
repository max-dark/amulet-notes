дерево переходов

index.php

g.php
    // форма логин/пароль
    ?site=main
        // новости
        ?site=news
        // вход в иргу
        ?site=connect&amp;login=$(nn:escape)&amp;p=$(pass:escape)&amp;rnd=rand()
            // вход для продолжения игры
            ?site=connect2&login=$login&p=$p
                ?site=connect&login=$login&p=$p&f_c=$f_c
                ?site=news
                    g.php
                // настройки
                ?sid=$sid&cnick=1
                // основная страница игры
                ?sid=$sid
                    // персонаж
                    ?sid=sid&amp;cl=i&amp;cj=1
                    // сказать
                    ?sid=sid&amp;cs=1&amp;cj=1
                    // контакты
                    ?sid=sid&amp;msg=1&amp;cj=1
                    // макросы
                    ?sid=sid&amp;cm=new
                    // выход с сохранением
                    ?sid=sid&amp;ce=1
                    // список переходов
                    ?sid=sid&amp;go=$(to)
                    // инфо о локе
                    g.php?sid=sid&amp;ci=1
                    // говаорить/взять
                    ?sid=sid&amp;cs=$(to)
                    // атака
                    ?sid=sid&amp;ca=$(to)
                    // предмет
                    sid=sid&amp;to=$(to)&amp;cl=i
                    // использовать
                    sid=sid&amp;to=$(to)&amp;use=
                    // информация о
                    g.php?sid=sid&amp;ci=$(to)
            include_once('f_site_connect2.dat');
        // форма регистрации
        ?site=gamereg&amp;log=new&amp;nn=$(nn:escape)
            // создание учетки
            ?site=reg2
                // вход в игру
                f_connect.php?login=$nn&p=$pass      FILE_NOT_FOUND
        // поиск игрока
        f_site_found.php?login=$(nn:escape)&r=rand() FILE_NOT_FOUND
        // статистика
        ?site=main3&r=rand();
        // ЧаВо
        ../f_faq.php FILE_NOT_FOUND
