<?php

    /**
     * This is class for connect, manage and database.
     *
     * @category   Yucat
     * @package    Includes
     * @name       db
     * @author     René Èinèura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.0
     */
?>

Funkcne stranky:
    inc\db
    inc\Diagnostics\Debug
    inc\Diagnostics\ErrorHandler
    Treba upravit...

Core updates:
    AJAX
    Form (jak v nette, nadefinovat form a potom tam dat aj chybove hlasky atd.)
    Potvrdzovanie mailom (activate_id do users a do last_login a ll2 vlozit datum registracie)
    Mazanie neaktivovanych uctov (Ak nema aktivovany ucet a je starsi ako 2 mesiace)
    Multi-language (Rozkuskovat jazyky do suborov napr Users.php co je pre classu Users atd.) bude to array a budu oznacene keys ako _ANY_TEXT
    +ExceptionHandler
    tzv. autologinovacie URL, nieco ako index.php?login=mike&hash=password, password je md5(login + createHash hesla)
    *Emailer, niekdo da odoslat mail a ten sa zapise do db  potom cron kazdych 10 sec posle mail
    Cron, Backup, Credit
    Uploader cez jQuery a graficky vykreslit
    jazyky sa budu brat v prioritach ako subdomena (cz.gshost.eu, sk.gshost.eu atd.), Users DB, predvolebny jazyk prehliadaca, CFG DEFAULT
    Brat domeny ako Zlozka/Presenter/method - lebo napr aby nebolo vsetko nadrbane v jednom a pre AJAX
    pridat na hl web yucat nejake api na zistenie  noviniek ohladom administrace a potom aby sa to vypisovalo kazdemu kdo pouziva muju adminku
    V configu orezavat medzeri alebo v sec treat pred a za value
    Spravit to tak ze kredity budu dostavat iba ked je clovek na tej istej stranke co maju v profile a kredity budu asi 1 ku 100 a moznost kupit cez sms
    Vzdy cez javascript on change odosielat ajaxovy poziadavok z menom napr. login, password, email atd. na server a kontrolovat dlzku znakov atd.
    
    Legenda: + - prioritne, * - mozny navrh
    
    
    

Add to lanugage translate
    PAGE_NOT_FOUND
    ERR_SET_STYLE
    ERR_MYSQL_CONNECT
    ERR_IS_NOT_NUMERIC

    
    
    
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




Standardy:
    Pouzivame NetBeans 7.0!

    nezatvarat php subory tagmy ?> lebo ked je za tym medzera a je includnuty tak pred <html> v php budu medzery
    optimalna dlzka kodu je 80 znakov, maximalna 120, ak je kod dlhsi jeho zlucenie s masterom bude zamietnute (pripadne upravene)
    pouziva sa tzv. camelCase pre classy, methody a nazvy premennych, prvePismenoMetodyJeVzdeMale($ajFunkcie)
    staticke metody sa pouzivaju minimalne!
    vzdy sa pise jak u metod tak u premenych opravneneie public/protection/private (u var sa nepouzova var $premena)
    prva zlozena zatvorka sa pise za parametre funkcie public function nejakaFunkcia($param) {
    
    
    }
    Nazov classy je vzdy velkym
    Vzdy sa pouzivaju jednoduche zatvorky, lebo php sa u zlozitich snazi prekladat obsah! " sa pouzivaju iba ked tam je napr \n alebo nieco pododbne
    'nejaky text ' . ' dalsi text ' . ' a tak dalej' - medzi ' a . je medzera
    Ak pisete premene pod seba tak
    $a = 'nejaka premenna'
       . 'a dalsia, kde bodla je pod ='
       
    Array sa pise
    array('jeden', 'dva', 'tri')
    array('jeden, 'dva',
          'tri')
    alebo
    array(
    jeden
    dva
    tri)