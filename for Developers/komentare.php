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
     * @deprecated Class deprecated in Release 0.0.0
     */
?>

Funkcne stranky:
    inc\db
    inc\Status
    inc\Date
    inc\Arr
    inc\Router

Robim na:
    Diagnostics
        Error handler
        Exception handler
    Sablonovaci system
        Open pages
        Macro    
    AJAX
    
    a treba classu text alebo string na pracu z textom

jazyky rozkuskovat do files a potom ich volat a predavat iba tie ktore su potrebne http://doc.nette.org/cs/components
uz nenahradzovat {neco} za array ale $this->template->neco;


Opravit sablonovaci sytem
strcit veci do temp
oddelit napr objednanie serveru atd od ostatnych
toto, one, pouzivat ajax na zistenie napr. max dlzok znakov vo formulari

Kuknut licenciu a pridat subor licence

Pridat active_id do tabulky users a do last_login zapisat datum registracie
Potvrdenie mailom inak sa nepojde prihlasit! a ak nebude mat potvrdeny ucet a bude starsi las login ako 2 mesiace tak sa mu deletne ucet


Add to lanugage translate
    PAGE_NOT_FOUND
    ERR_SET_STYLE
    ERR_MYSQL_CONNECT
    ERR_NOT_NUMERIC
    
    
    Pridat do konfigu time zone
    pridat presmerovanie do routru a generovanie adries
    Pridat potom update ako cez php