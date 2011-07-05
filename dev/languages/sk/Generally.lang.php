<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    $user = db::init()->uQuery(db::VIEWS, db::USERS, self::UID);

    if(empty($lang) || !is_Array($lang)) $lang = Array();

    $add = Array(
        //Template texts, descriptions, etc.
        'TITLE' => 'Administrácia serverov',
        'DESCRIPTION' => 'Game Server Hosting',
        'KEYWORDS' => 'Hosting, Host, Server Hosting, Server Host, Game Server Hosting, Game Server Host',
        
         //Login & forgot password form
        'T_LOGIN_USERNAME' => 'Username',
        'T_LOGIN_PASSWORD' => 'Password',
        'T_LOGIN_REMEMBER' => 'Trvalé prihlásenie',
        'T_LOGIN_ENTER' => 'Enter',
        'T_LOGIN_ENTER_MAIL' => 'Vložte Váš E-mail',
        'T_LOGIN_SEND_PASSWORD' => 'Poslať heslo',
        'T_LOGIN_FORGOT_PASSWORD' => 'Zabudly ste heslo?',
        'T_SUDDENDLY_REMEMBERED' => 'Vzpomenuly ste si?',

        //Generally messages and texts
        'T_HI' => 'Vitaj',
        'T_VISIT' => 'Visit website',
        'T_INSTRUCTION' => 'Inštrukcie',
        'T_INST_DESC' => 'Toto su testovacie inštrukcie ako používať našu novú adminku :D',
        'T_WELCOME_H' => 'Vitaj '.$user[db::USERS_LOGIN],
        'T_WELCOME_DESC' => 'Vitaj v Admin panely, <strong>UWAP '.CFG_VERSION.'</strong>! ktorý prezentuje Bloodman Arun pod OpenSource licenciou!',
        'T_SEND_MAIL' => '<hr /><br />Táto spráava bola odoslaná Administrátorm a neodpovedajte na ňu. Dakujem. <a href="'.CFG_WEBSITE.'">'.CFG_WEBSITE.'</a>',

        //News
        'T_NEWS_H' => 'Novinky',
        'T_NEWS_AUTHOR' => 'Napísal:',
        'T_NEWS_DAY' => 'Dňa:',
        'T_NEWS_TIME' => 'Čas:',
        'T_NEWS_UPDATES' => 'Updates',
        'T_NEWS_NOTIFICATIONS' => 'Notifications',
        'T_NEWS_SEARCH' => 'Search',

        //Stats
        'T_STATS_H' => 'Štatistiky',
        'T_STATS_USERS' => 'Užívatelia',
        'T_STATS_CREDIT' => 'Kredit v obehu',
        'T_STATS_VIEWS_BANNERS' => 'Skuknuté bannery',
        'T_STATS_SERVERS' => 'Hostované servery',
        
        //Auth
        'T_DONT_ORDER' => 'Free účet si nemôže objednať server!',
        'T_USR_LOCK_1' => '<h4>Block</h4>Váš účet bol zmrazený Administrátorom! Pre odblokovanie použite <a href="tickets">Tickety</a>, dakujem.',
        'T_SRV_LOCK_1' => 'Váš server bol zmrazený Administrátorom!',
        'T_SRV_LOCK_2' => 'Váš server bol pozastavený!',
        'T_SRV_LOCK_3' => 'Váš server bol zmrazený z dôvodu podozrenia o hacking!',
        
        //Ranks
        'T_PF_RANK_5' => 'Administrátor',
        'T_PF_RANK_4' => 'Moderátor',
        'T_PF_RANK_3' => 'V.I.P.',
        'T_PF_RANK_2' => 'Premium užívatel',
        'T_PF_RANK_1' => 'Business užívatel',
        'T_PF_RANK_0' => 'Bežný užívatel',

    );
    $lang = Array_Merge($lang, $add);
?>