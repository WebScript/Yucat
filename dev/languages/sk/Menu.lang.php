<?php
/**
 * @author Bloodman Arun
 * @copyright Copyright By Bloodman (c) 2011
 * @link http://gshost.eu
 * @site Language - Menu.lang
 */


if(empty($lang) || !is_Array($lang)) $lang = Array();

$add = Array(
    'T_MENU_MAIN' => 'Hlavný',
    'T_MENU_NEWS' => 'Novinky',
    'T_MENU_PROFILE' => 'Profil',
    'T_MENU_CHAT' => 'Pokec',
    'T_MENU_STATISTIC' => 'Statistiky',
    'T_MENU_TICKET' => 'Tickety',
    'T_MENU_ACCESS_LOG' => 'Access log',
    'T_MENU_CREDIT' => 'Kredit',
    'T_MENU_BUY_CREDIT' => 'Kúpiť kredit',
    'T_MENU_CODE_CREDIT' => 'Dobiť kredit',
    'T_MENU_SEND_CREDIT' => 'Poslať kredit',
    'T_MENU_VIEW_BNS' => 'Zobratiť bannery',
    'T_MENU_ORDER_M' => 'Objednávka',
    'T_MENU_ORDER_SERVER' => 'Objednať server',
    'T_MENU_DELETE_SERVER' => 'Zrušiť server',
    'T_MENU_SERVERS' => 'Servery',
    'T_MENU_CONTROL' => 'Ovládanie servera',
    'T_MENU_BACK' => 'Speť',
    'T_MENU_LOGOUT' => 'Odhlásiť',

    
    'T_MENU_SAMP_SETTING' => 'Nastavenie',
    'T_MENU_SAMP_CONFIG' => 'Config',
    'T_MENU_SAMP_FILE_CONTROL' => 'Kontrola',
    'T_MENU_SAMP_GRADE' => 'UP/Down Grade',

    'T_MENU_SAMP_LOG' => 'Logy',
    'T_MENU_SAMP_GAMELOG' => 'Game Log',
    'T_MENU_SAMP_BANLIST' => 'Banlist',

    'T_MENU_SAMP_FTP' => 'Web FTP',
    'T_MENU_SAMP_GM' => 'Gamemodes',
    'T_MENU_SAMP_FS' => 'Filterscripts',
    'T_MENU_SAMP_SF' => 'Scriptfiles',
    'T_MENU_SAMP_NPC' => 'NPC modes',

    'T_MENU_SAMP_STATUS' => 'Server Status',
    'T_MENU_SAMP_OSS' => 'Obrázkový Server Status',
    'T_MENU_SAMP_TSS' => 'Textový Server Status',
    'T_MENU_SAMP_ONLINE_PLS' => 'Online Hráči',

    'T_MENU_SAMP_BONUS' => 'Bonus',
    'T_MENU_SAMP_ONLINE_SERVERS' => 'Online Servery',
    'T_MENU_SAMP_INSTALLER' => 'Installer',
    'T_MENU_SAMP_API_BANLIST' => 'API Banlist',
    'T_MENU_SAMP_BACKUP' => 'Záloha'
);
$lang = Array_Merge($lang, $add);
?>