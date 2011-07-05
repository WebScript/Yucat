<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    if(empty($lang) || !is_Array($lang)) $lang = Array();

    $add = Array(
        'ERR_MYSQL_CONNECT' => 'Nepodarilo sa pripojiť k databázi!',
        'ERR_MAX_SIZE' => 'Přeskočili jste maximální povolení velikost!',
        'ERR_BAD_NAME' => 'Nemůžete nahrát na server soubor stímto názvem!',
        'ERR_BAD_SUFFIX' => 'Nemůžete nahrát na server soubor stouhle příponou!',
        'ERR_FILE_EXISTS' => 'Soůbor s tímto názvem už neexistuje!',
        'ERR_FILE_NO_EXISTS' => 'Takýto ssoubor neexistuje!',
        'ERR_BAN_FOLDER_NAME' => 'Nemůžete vytvořit složku stímto názvem!',
        'ERR_FOLDER_EXISTS' => 'Složka s stímto názvem už existuje!',
        'ERR_FOLDER_NO_EXISTS' => 'Tato složka neexistuje!',
        'ERR_NO_OPEN_FILE' => 'Nemůžete otevřít tento soubor!',
        'ERR_NO_EDIT_FILE' => 'Nemůžete upravit tento soubor!',
        'ERR_BAD_LOGIN' => 'Zadaly jste nezprávné jméno nebo heslo!',
        'ERR_BAD_IP' => 'Přihlašujete se z nepovolené IP adresy!',
        'ERR_WRONG_PASS' => 'Zadaly jste nezprávné heslo!',
        'ERR_ENTERED_VALUE_EQUAL' => 'Zadaný hesla se neschodují!',
        'ERR_WRONG_SET_VALUES' => 'Nezprávně vyplněné údaje!',
        'ERR_NO_SET_ALL' => 'Nevyplnily jste všechny poviné údaje!',
        'ERR_CHAT_BAN' => 'Administrátor ti zakázal pridávat správy do pokecu!',
        'ERR_LARGE_VALUE' => 'Příliš vysoká hodnota!',
        'ERR_RULES_ACCEPT' => 'Musíte souhlasit z podmienkami!',
        'ERR_CANT_ORDER' => 'Nemůžete si objednat tento server!',
        'ERR_IS_FULL_SERVERS' => 'Není volné místo pro server!',
        'ERR_HAVE_TOO_MANY_SERVERS' => 'Nemůžete si obědnat víc serveru!',
        'ERR_DONT_HAVE_CREDIT' => 'Nemáte dostatek kreditu!',
        'ERR_NOT_SET_SERVER' => 'Nebyla vybrana hra!',
        'ERR_SERVER_NO_EXISTS' => 'Server neexistuje!',
        'ERR_WRONG_CODE' => 'Špatně zadaný kód!',
        'ERR_CODE_ALREADY_USED' => 'Tento kód už byl použitý!',
        'ERR_NEW_RULES' => 'Změnily se pravidla servera, pokud nesouhlasíte nemůžete používat naše služby!',
        'ERR_WRONG_LANGUAGE' => 'Špatně vybraný jazyk!',
        'ERR_USER_ALREADY_EXISTS' => 'Zadaný přihlašovací jméno už někdo používá!',
        'ERR_INVALID_PHONE' => 'Špatně vyplněno tel. číslo!',
        'ERR_ALREADY_REGISTRED' => 'Už jste zaregistrován!',
        'ERR_INCORRECT_EMAIL' => 'Nezadaly jste správny E-mail!',
        'ERR_NO_HAVE_SERVER' => 'Nevlastníte žádný server!',
        'ERR_IS_RUNING' => 'Server už je zapnutý!',
        'ERR_NO_RUNING' => 'Server už je vypnutý!',
        'ERR_NO_EXISTS_FILES' => 'Nejsou vytvořeny všechny potřebné soubory, jděte do Nastavenie > kontrola a vytvoře je!',
        'ERR_FAILED_START' => 'Nepodařilo se zapnout server!',
        'ERR_TOO_MANY_SLOTS' => 'Překločily jste maximální počet slotů!',
        'ERR_TO_MANY_NPC' => 'Překločily jste maximální počet NPC!',
        'ERR_MUST_SELECT_VERSION' => 'Vyberte zprávnou verzi serveru!',
        'ERR_UPLOAD_FTP' => 'Nepodařilo se nahrát soubor na server!',
        'ERR_SSH_NO_CONNECTED' => 'Nepodařilo se připojit na server!',
        'ERR_SET_STYLE' => 'Nepodařilo sa vybrat defaultní templatu, prosím kontaktujte Administrátora!',
        'ERR_NOT_EXEC' => 'Nepořadilo se spustit prikaz!',
        'ERR_FAILED_CREATE_FOLDER' => 'Nepodařilo se vytvorit složku!',

        'CHAT_ADD_MESSAGE' => 'Napsal jsi zprávu do pokecu.',
        'CHANGE_PASS_OK' => 'Změna hesla prebehla v pořádku.',
        'SERVER_ORDERED' => 'Objednaly jste si server.',
        'SAVE_OK' => 'Údaje byly úspěšne uloženy',
        'DELETE_SERVER_OK' => 'Úspešne jste smazali server/y',
        'REGISTRATION_OK' => 'Registrace prebehla v poriadku, můžete sa přihlásit',
        'OK_START_SVR' => 'Zaply jste server',
        'OK_STOP_SVR' => 'Vyply jste server',
        'OK_EDIT_CONFIG' => 'Upravily jste konfigurační soubor',
        'OK_FIXED_SRV' => 'Opravily jste svoj server',
        'OK_CHANGE_VERSION' => 'Zmenily ste verzi servera',
        'OK_GLOG_SAVE' => 'Úspešne jste ulo�iliy server log',
        'OK_GLOG_DELETE' => 'Úspešne jste zmazaly server log',
        'OK_GLOG_DOWNLOAD' => 'Úspešne jste stiahly server log',
        'OK_BANLIST_DELETE' => 'Úspešne jste odbanoval hráče',
        'OK_UPLOADED_FTP' => 'Úspešne jste nahraly soubor na váš server',
        'OK_FTP_DELETE' => 'Úspešne jste smazaly soubor/y z vašeho serveru',
        'OK_FTP_MKDIR' => 'Úspešne jste vytvorily složku na vašem serveru',
        'OK_FTP_SC_EDIT' => 'Úspešne jste upravily soubor v scriptfiles',
        'OK_BACKUP_DOWNLOADED' => 'Stáhly jste zálohu'
    );

    $lang = Array_Merge($lang, $add);
?>