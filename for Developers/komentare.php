<?php

    /**
     * This is class for connect, manage and database.
     *
     * @category   Yucat
     * @package    Includes
     * @name       db
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Yucat Technologies (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.0
     */
?>

    Pouzivat MVP nie MVC!


Funkcne stranky:
    inc\db

Core updates:
    AJAX
    Sablonovaci system
    Presenter
    Form (jak v nette, nadefinovat form a potom tam dat aj chybove hlasky atd.)
    Licenciu vlozit, asi GNU GPL v2
    Potvrdzovanie mailom (activate_id do users a do last_login a ll2 vlozit datum registracie)
    Mazanie neaktivovanych uctov (Ak nema aktivovany ucet a je starsi ako 2 mesiace)
    Multi-language (Rozkuskovat jazyky do suborov napr Users.php co je pre classu Users atd.)
    Premenne nastavovat cez $this->template->neco a vo VIEW {$neco} (aj M-Language)
    Updatevit config-example na zaklade config.php
    Router (Pridat redirect a aj prerobit trochu tie adresovania!)
    
 

Add to lanugage translate
    PAGE_NOT_FOUND
    ERR_SET_STYLE
    ERR_MYSQL_CONNECT
    ERR_NOT_NUMERIC

    
    
    
Funkcionalne updaty:
    Posielanie kreditu + do logu transakcie
    Pridat do registracie povinny vek
    Verejny profil v takom tom mini okienku (pre admina ban/lock, pre uzivatelov send credits)
    Do profilu si moyu ludia dat MotD
    Formatovanie tabuliek
    Friends/Unfriends
    PM spravy
    Vlastne avatary
    Mini-forum   
    SAMP:
        kontrola chmodu samp03sv a announce



        
HL menu dat ako
Hlavne
Statistiky
Server
Tickets
Mini-forum


Hlavne: 
Profil, zmena hesla


Statistiky:
Skusknete bannery
posledne transakcie kreditu
NAvstevnost serverov
Access log


Tickets:
Podanie ticketu
Podane tickety - tabulka = lockunte, riesia sa, neotvorene, zrusene


Mini-forum:
je to vlastne pokec kde si mozete zakladat vlastne temy a odpovedat na kazdu



NEWS - nespracovane !! :
Vzdy cez javascript on change odosielat ajaxovy poziadavok z menom napr. login, password, email atd. na server a kontrolovat dlzku znakov atd.
