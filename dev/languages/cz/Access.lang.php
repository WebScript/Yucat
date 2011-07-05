<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    if(empty($lang) || !is_Array($lang)) $lang = Array();

    $add = Array(
        'AC_LOGIN' => 'Přihlášení do adminsitrace.',
        'AC_ADD_CHAT_MESSAGE' => 'Vložení zprávy do pokecu.',
        'AC_ACCEPTED_RULES' => 'Přijetí podmínek.',
        'AC_ORDER_SERVER' => 'Objednání serveru.',
        'AC_RECHANGE_CREDIT' => 'Použití dobíjecího kódu.',
        'AC_CHANGE_PASSWORD' => 'Změna hesla.',
        'AC_EDIT_PROFILE' => 'Úprava profilu.',
        'AC_DELETE_SERVER' => 'Smazání servera/u.',
        'AC_START_SVR' => 'Zapnutí serveru.',
        'AC_STOP_SVR' => 'Vypnutí serveru.',
        'AC_EDIT_CONFIG' => 'Úprava configuračného souboru.',
        'AC_FIXED_SRV' => 'Oprava serveru.',
        'AC_CHANGE_VERSION' => 'Změna verze serveru.',
        'AC_GLOG_SAVE' => 'Uložení server logu.',
        'AC_GLOG_DELETE' => 'Smazání server logu.',
        'AC_GLOG_DOWNLOAD' => 'Stažení server logu.',
        'AC_BANLIST_DELETE' => 'Smazání údajů z banlistu.',
        'AC_UPLADED_FTP' => 'Uploadnutie souboru na server.',
        'AC_FTP_DELETE' => 'Smazání soubora/u ze serveru.',
        'AC_FTP_MKDIR' => 'Vytvoření složky na servery.',
        'AC_FTP_SC_EDIT' => 'Úprava souboru v scriptfiles.',
        'AC_BACKUP_DOWNLOAD' => 'Stažení zálohy.',
        'AC_INSTALLED' => 'Instalace módu / scriptu z installer.'
    );
    $lang = Array_Merge($lang, $add);
?>