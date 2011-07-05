<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

     use inc\db;

    $user = db::_init()->uQuery(db::VIEWS, db::USERS, self::UID);

    if(empty($lang) || !is_Array($lang)) $lang = Array();

    $add = Array(
        //Template texts, descriptions, etc.
        'TITLE' => 'Adminsitrace servera',
        'DESCRIPTION' => 'Game Server Hosting',
        'KEYWORDS' => 'Hosting, Host, Server Hosting, Server Host, Game Server Hosting, Game Server Host',
        
         //Login & forgot password form
        'T_LOGIN_USERNAME' => 'Username',
        'T_LOGIN_PASSWORD' => 'Password',
        'T_LOGIN_REMEMBER' => 'Trvalé přihlášení',
        'T_LOGIN_ENTER' => 'Enter',
        'T_LOGIN_ENTER_MAIL' => 'Vložte Váš E-mail',
        'T_LOGIN_SEND_PASSWORD' => 'Poslat heslo',
        'T_LOGIN_FORGOT_PASSWORD' => 'Zapomněly jste heslo?',
        'T_SUDDENDLY_REMEMBERED' => 'Vzpomenuly jste si?',

        //Generally messages and texts
        'T_HI' => 'Vitaj',
        'T_VISIT' => 'Visit website',
        'T_INSTRUCTION' => 'Instrukce',
        'T_INST_DESC' => 'Adminsitrace se jen testuje :D',
        'T_WELCOME_H' => 'Vitaj '.$user[db::USERS_LOGIN],
        'T_WELCOME_DESC' => 'Vitaj v Admin panely, <strong>UWAP '.CFG_VERSION.'</strong>! ktorý prezentuje Bloodman Arun pod OpenSource licencí!',
        'T_SEND_MAIL' => '<hr /><br />Tato zpráva byla poslána administrátorem neodpovídejte na ní. Dakujem. <a href="'.CFG_WEBSITE.'">'.CFG_WEBSITE.'</a>',

        //News
        'T_NEWS_H' => 'Novinky',
        'T_NEWS_AUTHOR' => 'Napísal:',
        'T_NEWS_DAY' => 'Den:',
        'T_NEWS_TIME' => 'Čas:',
        'T_NEWS_UPDATES' => 'Updates',
        'T_NEWS_NOTIFICATIONS' => 'Notifications',
        'T_NEWS_SEARCH' => 'Search',

        //Stats
        'T_STATS_H' => 'Statistiky',
        'T_STATS_USERS' => 'Užívatelé',
        'T_STATS_CREDIT' => 'Kredit v obehu',
        'T_STATS_VIEWS_BANNERS' => 'Zobrazené bannery',
        'T_STATS_SERVERS' => 'Hostované servery',
        
        //Auth
        'T_DONT_ORDER' => 'Free účet si nemůže objednat server!',
        'T_USR_LOCK_1' => '<h4>Váš účet byl zmrazen</h4>Váš účet byl zmrazený Administrátorom!',
        'T_SRV_LOCK_1' => '<h4>Váš účet byl zmrazen</h4>Váš server byl zmrazený Administrátorom!',
        'T_SRV_LOCK_2' => '<h4>Váš účet byl zmrazen</h4>Váš server byl pozastavený!',
        'T_SRV_LOCK_3' => '<h4>Váš účet byl zmrazen</h4>Váš server byl zmrazený z důvodu podozrení o hacking!'

    );
    $lang = Array_Merge($lang, $add);
?>