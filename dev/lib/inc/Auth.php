<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

     /**
      * @todo dorobit dokumentaciu\
      * @todo opravit funkcie
      * @todo prerobit md5 na vlastnu funkciu ktora bude hashovat hesla 
      */

    namespace inc;

    class Auth {
        
        //access type
        const HEAD = 'head';
        const SAMP = 'SAMP';
        
        const UID = UID;
        const UIP = UIP;

        
        private function __construct() {}
        
        
        /**
         * Function for login
         * @param string $username username to login to administration
         * @param string $password password to login to administration
         * @param string $remember if is set so script has remeber login
         */
        public static function login($username, $password, $remember = FALSE) {
            $user = db::_init()->q(db::FETCH_ARRAY, db::_init()->uQuery(db::VIEWS, db::USERS, Array(db::USERS_LOGIN => $username, db::USERS_PASSWORD => Security::createHash($password))));
            if(!$user) Message(ERR_BAD_LOGIN);
            
            $time = $remember ? time() + 31104000 : 0;

            setcookie('logged', TRUE, $time, '/', CFG_WEBSITE);
            setcookie('id', $user[db::USERS_ID], $time, '/', CFG_WEBSITE);
            setcookie('password', $user[db::USERS_PASSWORD], $time, '/', CFG_WEBSITE);
            define('UID', $_COOKIE['id']);
            self::access(self::HEAD, AC_LOGIN);
            self::setLastLogin($user[db::USERS_ID]);
        }
        
        
        /**
         * Function for logout
         */
        public static function logout() {
            setcookie('logged', '0', 0, '/', CFG_WEBSITE);
            setcookie('id', '0', 0, '/', CFG_WEBSITE);
            setcookie('password', '0', 0, '/', CFG_WEBSITE);
            session_destroy();
            self::access(self::HEAD, AC_LOGOUT);
        }
        
        
        /**
         * register to DB
         * @param $array array of parameters and values
         * @todo asi prehodit do indexu
         */
        public static function register(array $array) {
            if(!array_key_exists($array[db::USERS_LANGUAGE], Lang::$avaiable_language)) Message(ERR_WRONG_LANGUAGE);
            if($array[db::USERS_PASSWORD] != $array['retry_password']) Message(ERR_ENTERED_VALUE_EQUAL);
            if(!Security::checkEmail($array[db::USERS_EMAIL])) Message(ERR_INCORRECT_EMAIL);
            
            if(db::_init()->q(db::FETCH_ARRAY, db::_init()->uQuery(db::VIEWS, db::USERS, Array(db::USERS_LOGIN => $array[db::USERS_LOGIN])))) Message(ERR_USER_ALREADY_EXISTS);
            if(db::_init()->q(db::FETCH_ARRAY, db::_init()->uQuery(db::VIEWS, db::USERS, Array(db::USERS_EMAIL => $array[db::USERS_EMAIL])))) Message(ERR_EMAIL_ALREADY_REGISTRED);
            if(db::_init()->q(db::FETCH_ARRAY, db::_init()->uQuery(db::VIEWS, db::USERS, Array(db::USERS_IP => UIP)))) Message(ERR_ALREADY_REGISTRED);

            $array[db::USERS_PASSWORD] = Security::createHash($array[db::USERS_PASSWORD]);
            
            $add = array(
                db::USERS_AVATAR => '1',
                db::USERS_RANK   => '0',
                db::USERS_RULES  => '0',
                db::USERS_CREDIT => '0',
                db::USERS_IP     => self::UIP,
                db::USERS_CHAT   => '1',
                db::USERS_LOCK   => '0',
                db::USERS_STYLE  => CFG_DEF_STYLE
            );
            
            $check = array(
                db::USERS_LOGIN,
                db::USERS_PASSWORD,
                db::USERS_FIRSTNAME,
                db::USERS_LASTNAME,
                db::USERS_ADDRESS,
                db::USERS_LANGUAGE,
                db::USERS_CITY,
                db::USERS_POSTCODE,
                db::USERS_TELEPHONE,
                db::USERS_EMAIL,
                db::USERS_WEBSITE,
                db::USERS_AVATAR,
                db::USERS_RANK,
                db::USERS_RULES,
                db::USERS_CREDIT,
                db::USERS_IP,
                db::USERS_CHAT,
                db::USERS_LOCK,
                db::USERS_STYLE
            );
            
            $array = array_merge($array, $add);
            if(!Arr::isInArray($array, $check)) Message(ERR_NO_SET_ALL);
            db::_init()->uQuery(db::ADD, db::USERS, $array);
            Message(REGISTRATION_OK, 3);
        }


        /**
         *
         * @param $id id of user
         */
        public static function setLastLogin($id) {
            $date = date('U', time());
            $user = db::_init()->uQuery(db::VIEWS, db::USERS, $id);

            if($user[db::USERS_LL2] != $date) {
                db::_init()->uQuery(db::UPDATE, db::USERS, Array(db::USERS_LL => $user[db::USERS_LL2], db::USERS_LL2 => $date), Array(db::USERS_ID => $id));
            }

            //if user is new registred so set last_login & last_login_2 to same date
            if(!$user[db::USERS_LL]) db::_init()->uQuery(db::UPDATE, db::USERS, Array(db::USERS_LL => $date), Array(db::USERS_ID => $id));
            if(!$user[db::USERS_LL2]) db::_init()->uQuery(db::UPDATE, db::USERS, Array(db::USERS_LL2 => $date), Array(db::USERS_ID => $id));
        }


        /**
         * Check if user is loged and if he has validated password
         * check if he has accepting rules
         * @global $etc id of server if is set
         * @global $user user's params
         */
        public static function isLogged() {
            $user = db::_init()->uQuery(db::VIEWS, db::USERS, self::UID);
            
            if($_COOKIE['logged']) {
                if(!$_COOKIE['id'] || !$_COOKIE['password'] || $user[db::USERS_PASSWORD] != $_COOKIE['password']) {
                    Auth::Logout();
                    header('refresh: 0');
                }

                if(Page::getAddress(0) == 'user' && Page::getAddress(1) != 'rules') {
                    if(!$user[db::USERS_RULES]) Page::goToPage('user/rules');
                }
            }
        }


        /**
         * chcecking if user or server doesn't have locked server or account
         * @global $db connect to DB
         * @global $user user's params
         * @param $type type of chceck, if he has logged on user or server
         */
        public static function lockCheck() {
            $etc = Page::getAddress();
            $user = db::_init()->uQuery(db::VIEWS, db::USERS, self::UID);
            
            if($etc[0] == 'user') { //User
                if($etc[1] != 'rules' && $etc[1] != 'tickets' && $etc[1] != 'logout') {
                    switch($user[db::USERS_LOCK]) {
                        case '1' : 
                            Message(T_USR_LOCK_1, 1, 'no');
                        break;
                    }
                }
            } elseif($etc[0] == 'server') { //Server
                $srv = db::_init()->uQuery('VIEW', 'servers', $etc[1]);

                switch($srv[db::SERVERS_LOCK]) {
                    case 1 : 
                        Message(T_SRV_LOCK_1, 1, 'no');
                    break;
                    case 2 : 
                        Message(T_SRV_LOCK_2, 1, 'no');
                    break;
                    case 3 : 
                        Mesaage(T_SRV_LOCK_2, 1, 'no');
                    break;
                }
            }
        }
        
        /**
         * Add user's actions to log
         * @param $type type of access log
         * @param $action user's action
         */
        public static function access($type, $action) {
            $date = Date('U', Time());
                        
            db::_init()->uQuery(db::ADD, db::ACCESS, Array(
                db::ACCESS_WHO    => self::UID,
                db::ACCESS_TYPE   => $type,
                db::ACCESS_ACTION => $action,
                db::ACCESS_DATE   => $date,
                db::ACCESS_IP     => self::UIP
            ));
        }
    }
    
    

