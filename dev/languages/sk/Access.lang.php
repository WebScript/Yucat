<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    if(empty($lang) || !is_Array($lang)) $lang = Array();

    $add = Array(
        'AC_LOGIN' => 'Login do administrácie.',
        'AC_ADD_CHAT_MESSAGE' => 'Vloženie správy do pokecu.',
        'AC_ACCEPTED_RULES' => 'Prijatie podmienok.',
        'AC_ORDER_SERVER' => 'Objednanie servera.',
        'AC_RECHANGE_CREDIT' => 'Použitie dobíjacieho kódu.',
        'AC_CHANGE_PASSWORD' => 'Zmena hesla.',
        'AC_EDIT_PROFILE' => 'Úprava profilu.',
        'AC_DELETE_SERVER' => 'Zmazanie servera/ov.',
        'AC_START_SVR' => 'SAMP: Zapnutie servera.',
        'AC_STOP_SVR' => 'SAMP: Vypnutie servera.',
        'AC_EDIT_CONFIG' => 'SAMP: Úprava configuračného súboru.',
        'AC_FIXED_SRV' => 'SAMP: Oprava servera.',
        'AC_CHANGE_VERSION' => 'SAMP: Zmena verzie servera.',
        'AC_GLOG_SAVE' => 'SAMP: Uloženie server logu.',
        'AC_GLOG_DELETE' => 'SAMP: Zmazanie server logu.',
        'AC_GLOG_DOWNLOAD' => 'SAMP: Stiahnutie server logu.',
        'AC_BANLIST_DELETE' => 'SAMP: Zmazanie údajov z banlistu.',
        'AC_UPLADED_FTP' => 'SAMP: Uploadnutie súboru na server.',
        'AC_FTP_DELETE' => 'SAMP: Zmazanie súboru/ov zo servera.',
        'AC_FTP_MKDIR' => 'SAMP: Vytvorenie zložky na servery.',
        'AC_FTP_SC_EDIT' => 'SAMP: Úprava súboru v scriptfiles.',
        'AC_BACKUP_DOWNLOAD' => 'Stiahnutie zálohy.',
        'AC_INSTALLED' => 'SAMP: Nainštalovanie módu/scriptu z installeru.'
    );
    $lang = Array_Merge($lang, $add);
?>