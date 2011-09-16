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
Add to lanugage translate
    PAGE_NOT_FOUND
    ERR_SET_STYLE
    ERR_MYSQL_CONNECT
        
DB:
    UID - User ID
    MID - Machine ID
    SID - Server ID

HL Menu:
    Hlavne: 
        +Novinky
        Profil

    Statistiky:
        Skuknete bannery
        Posledne transakcie kreditu
        Navstevnost serverov
        Access log

    Tickets (Bug report):
        Podanie ticketu
        Podane tickety - tabulka = lockunte, riesia sa, neotvorene, zrusene

    Mini-forum:
        Je to vlastne pokec kde si mozete zakladat vlastne temy a odpovedat na kazdu

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