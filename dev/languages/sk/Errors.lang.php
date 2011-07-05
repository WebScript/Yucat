<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    if(empty($lang) || !is_Array($lang)) $lang = Array();

    $add = Array(
        'ERR_MYSQL_CONNECT' => 'Nepodarilo sa pripojiť k databázi!',
        'ERR_MAX_SIZE' => 'Preskočili ste maxim�lnu povolen� ve�kos�!',
        'ERR_BAD_NAME' => 'Nem��ete nahra� s�bor z tak�mto n�zvom!',
        'ERR_BAD_SUFFIX' => 'Nem�zete nahra� s�bor z takouto pr�ponou!',
        'ERR_FILE_EXISTS' => 'S�bor zo zadan�m menom u� existuje!',
        'ERR_FILE_NO_EXISTS' => 'Tak�to s�bor neexistuje!',
        'ERR_BAN_FOLDER_NAME' => 'Nemo�te vytvori� zlo�ku z tak�mto menom!',
        'ERR_FOLDER_EXISTS' => 'Zlo�ka z tak�mto menom u� existuje!',
        'ERR_FOLDER_NO_EXISTS' => 'Tak�to zlo�ka neexistuje!',
        'ERR_NO_OPEN_FILE' => 'Nem��ete otvori� tento s�bor!',
        'ERR_NO_EDIT_FILE' => 'Nem��ete upravi� tento s�bor!',
        'ERR_BAD_LOGIN' => 'Zadali ste zl� meno alebo heslo!',
        'ERR_BAD_IP' => 'Prihlasujete sa z nepovolenej IP Adresy!',
        'ERR_WRONG_PASS' => 'Zadali ste zl� heslo!',
        'ERR_ENTERED_VALUE_EQUAL' => 'Zadan� hesl� sa nerovnaj�!',
        'ERR_WRONG_SET_VALUES' => 'Zle vyplnen� �daje!',
        'ERR_NO_SET_ALL' => 'Nezadali ste v�etky povinn� �daje!',
        'ERR_CHAT_BAN' => 'Administr�tor ti zak�zal prid�va� spr�vy do pokecu!',
        'ERR_LARGE_VALUE' => 'Pr�li� vysok� hodnota!',
        'ERR_RULES_ACCEPT' => 'Mus�te s�hlasi� z podmienkami!',
        'ERR_CANT_ORDER' => 'Nemo�e� si objedna� tento server!',
        'ERR_IS_FULL_SERVERS' => 'Neni vo�n� miesto na serveri!',
        'ERR_HAVE_TOO_MANY_SERVERS' => 'Nem��ete si objedna� viac serverov!',
        'ERR_DONT_HAVE_CREDIT' => 'Nem�te dostatok kreditu!',
        'ERR_NOT_SET_SERVER' => 'Nebola vybran� �iadna hra!',
        'ERR_SERVER_NO_EXISTS' => 'Server neexistuje!',
        'ERR_WRONG_CODE' => 'Zle zadan� k�d!',
        'ERR_CODE_ALREADY_USED' => 'Tento k�d u� bol raz pou�it�!',
        'ERR_NEW_RULES' => 'Zmenili sa podmienky servera, ak s nimi nes�hlas�te tak nesmiete na�alej pou��va� na�e slu�by!',
        'ERR_WRONG_LANGUAGE' => 'Zle vybran� jazyk!',
        'ERR_USER_ALREADY_EXISTS' => 'Zadan� login u� niekdo pou��va!',
        'ERR_INVALID_PHONE' => 'Zle zadan� telef�nne ��slo!',
        'ERR_ALREADY_REGISTRED' => 'U� ste raz zaregistrovan�!',
        'ERR_INCORRECT_EMAIL' => 'Nezadaly ste spr�vny E-mail!',
        'ERR_NO_HAVE_SERVER' => 'Nevlastn� �iadny server!',
        'ERR_IS_RUNING' => 'Server je u� zapnut�!',
        'ERR_NO_RUNING' => 'Server je u� vypnut�!',
        'ERR_NO_EXISTS_FILES' => 'Nie su vytvoren� v�etky potrebn� s�bory, cho� do Nastavenie > kontrola a vytvor ich!',
        'ERR_FAILED_START' => 'Nepodarilo sa zapn� server!',
        'ERR_TOO_MANY_SLOTS' => 'Presko�ili ste maxim�lny po�et slotov!',
        'ERR_TO_MANY_NPC' => 'Presko�ili ste maxim�lny po�et NPC!',
        'ERR_MUST_SELECT_VERSION' => 'Mus� vybra� spr�vnu verziu!',
        'ERR_UPLOAD_FTP' => 'Nepodarilo sa nahra� s�bor na server!',
        'ERR_SSH_NO_CONNECTED' => 'Nepodarilo sa pripoji� na server!',
        'ERR_SET_STYLE' => 'Nepodarilo sa vybra� defaultn� templatu, pros�m kontaktujte Administr�tora!',
        'ERR_NOT_EXEC' => 'Neporadilo sa spustit prikaz!',
        'ERR_FAILED_CREATE_FOLDER' => 'Nepodarilo sa vytvoriť zložku!',

        'CHAT_ADD_MESSAGE' => 'Nap�sal si spr�vu do pokecu.',
        'CHANGE_PASS_OK' => 'Zmena hesla prebehla v poriadku.',
        'SERVER_ORDERED' => 'Objednali ste si server.',
        'SAVE_OK' => '�daje boli �spe�ne ulo�en�',
        'DELETE_SERVER_OK' => '�spe�ne ste zmazali server/y',
        'REGISTRATION_OK' => 'Registr�cia prebehla v poriadku, m��ete sa prihl�si�',
        'OK_START_SVR' => 'Zapli ste server',
        'OK_STOP_SVR' => 'Vypli ste server',
        'OK_EDIT_CONFIG' => 'Upravili ste konfigura�n� s�bor',
        'OK_FIXED_SRV' => 'Opravili ste svoj server',
        'OK_CHANGE_VERSION' => 'Zmenili ste verziu servera',
        'OK_GLOG_SAVE' => '�spe�ne ste ulo�ili server log',
        'OK_GLOG_DELETE' => '�spe�ne ste zmazali server log',
        'OK_GLOG_DOWNLOAD' => '�spe�ne ste stiahli server log',
        'OK_BANLIST_DELETE' => '�spe�ne ste odbanoval hr��a/ov',
        'OK_UPLOADED_FTP' => '�spe�ne ste nahrali s�bor na V� server',
        'OK_FTP_DELETE' => '�pse�ne ste zmazali s�bor/y z Va�eho servera',
        'OK_FTP_MKDIR' => '�spe�ne ste vytvorili zlo�ku na Va�om servery',
        'OK_FTP_SC_EDIT' => '�spe�ne ste upravili s�bor v scriptfiles',
        'OK_BACKUP_DOWNLOADED' => 'Stiahli ste z�lohu'
    );

    $lang = Array_Merge($lang, $add);
?>