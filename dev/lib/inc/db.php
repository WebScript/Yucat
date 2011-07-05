<?php
    namespace inc;

    /**
     * This is class for connect, manage and database.
     *
     * @category   Yucat
     * @package    Includes
     * @name       db
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Yucat Technologies (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.3.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.3.2
     * @deprecated Class deprecated in Release 0.0.0
     * 
     * @todo Dorobit dokumentaciu
     */

    class db {
        /** @var variable for singleton */
        private static $__instance = FALSE;
        /** @var ressource of connection to DB */
        private static $connection;
        
        
        /**
         * Constants of names for sql tables
         */
        const USERS             = 'users';
        const SERVERS           = 'servers';
        const MACHINES          = 'machines';
        const ACCESS            = 'access';
        const BANNERS           = 'banners';
        const CCREDITS          = 'codecredits';
        const MESSAGES          = 'messages';
        const CHAT              = 'chat';
        const TICKETS           = 'tickets';
        const BANNED            = 'banned';
        const ERRORLOG          = 'error_logs';

        
        /**
         * Constnts of names for users table in sql tables
         */
        const USERS_ID          = 'id';
        const USERS_LOGIN       = 'login';
        const USERS_PASSWORD    = 'password';
        const USERS_FIRSTNAME   = 'firstname';
        const USERS_LASTNAME    = 'lastname';
        const USERS_ADDRESS     = 'address';
        const USERS_CITY        = 'city';
        const USERS_POSTCODE    = 'postcode';
        const USERS_TELEPHONE   = 'telephone';
        const USERS_CREDIT      = 'credit';
        const USERS_LANGUAGE    = 'language';
        const USERS_STYLE       = 'style';
        const USERS_AVATAR      = 'avatar';
        const USERS_RANK        = 'rank';
        const USERS_RULES       = 'rules';
        const USERS_EMAIL       = 'email';
        const USERS_WEBSITE     = 'website';
        const USERS_IP          = 'ip';
        const USERS_LL          = 'last_login';
        const USERS_LL2         = 'last_login2';
        const USERS_CHAT        = 'chat';
        const USERS_LOCK        = 'lock';
        
        /**
         * Constnts of names for servers table in sql tables
         */
        const SERVERS_ID        = 'id';
        const SERVERS_OWNER     = 'owner';
        const SERVERS_TYPE      = 'type';
        const SERVERS_MACHINE   = 'machine';
        const SERVERS_PORT      = 'port';
        const SERVERS_SLOTS     = 'slots';
        const SERVERS_LOCK      = 'lock';
        const SERVERS_STOPPED   = 'stoped';
        const SERVERS_AUTORUN   = 'autorun';
        
        /**
         * Constnts of names for machines table in sql tables
         */
        const MACHINES_ID       = 'id';
        const MACHINES_NAME     = 'name';
        const MACHINES_HOSTNAME = 'hostname';
        const MACHINES_IP       = 'ip';
        const MACHINES_LOGIN    = 'ssh_login';
        const MACHINES_PASS     = 'ssh_password';
        const MACHINES_SAMP     = 'SAMP';
        const MACHINES_VNT      = 'VNT';
        const MACHINES_RANK     = 'rank';
        
        /**
         * Constnts of names for access table in sql tables
         */
        const ACCESS_ID         = 'id';
        const ACCESS_WHO        = 'who';
        const ACCESS_TYPE       = 'type';
        const ACCESS_ACTION     = 'action';
        const ACCESS_IP         = 'ip';
        const ACCESS_DATE       = 'date';
        
        /**
         * Constnts of names for banners table in sql tables
         */
        const BANNERS_ID        = 'id';
        const BANNERS_WHO       = 'who';
        const BANNERS_DATE      = 'date';
        const BANNERS_SIZE      = 'size';
        const BANNERS_URL       = 'web';
        const BANNERS_IP        = 'ip';
        
        /**
         * Constnts of names for codecredits table in sql tables
         */
        const CCREDITS_ID       = 'id';
        const CCREDITS_1        = '1';
        const CCREDITS_2        = '2';
        const CCREDITs_3        = '3';
        const CCREDITs_LOCK     = 'lock';
        const CCREDITS_COST     = 'cost';
        
        /**
         * Constnts of names for messages table in sql tables
         */
        const MESSAGES_ID       = 'id';
        const MESSAGES_DATE     = 'date';
        const MESSAGES_TIME     = 'time';
        const MESSAGES_WHO      = 'who';
        const MESSAGES_TITLE    = 'title';
        const MESSAGES_MESSAGE  = 'message';
        const MESSAGES_TYPE     = 'type';
        const MESSAGES_VALUE    = 'value';
        
        /**
         * Constnts of names for chat table in sql tables
         */
        const CHAT_ID           = 'id';
        const CHAT_WHO          = 'who';
        const CHAT_DATE         = 'date';
        const CHAT_MESSAGE      = 'message';
        
        /**
         * Constnts of names for tickets table in sql tables
         */
        const TICKETS_ID        = 'id';
        const TICKETS_WHO       = 'who';
        const TICKETS_TYPE      = 'type';
        const TICKETS_CATEGORY  = 'category';
        const TICKETS_MESSAGE   = 'message';
        const TICKETS_LOCK      = 'lock';
        
        /**
         * Constnts of names for banned table in sql tables
         */
        const BANNED_ID         = 'id';
        const BANNED_IP         = 'ip';
        const BANNED_DATE       = 'date';
        const BANNED_MESSAGE    = 'message';
        
        
        /**
         * Constants for uQuery function
         */
        const ADD               = 'add';
        const VIEWS             = 'views';
        const UPDATE            = 'update';
        const DELETE            = 'delete';
        
        /**
         * Constants for q function
         */
        const QUERY             = 'query';
        const FETCH_ARRAY       = 'fetch_array';
        const FETCH_OBJECT      = 'fetch_object';
        const NUM_ROWS          = 'num_rows';
        
        /**
         * Constnts of names for error_log table in sql tables
         */
        const ERRORLOG_ID       = 'id';
        const ERRORLOG_DATE     = 'date';
        const ERRORLOG_TYPE     = 'type';
        const ERRORLOG_MESSAGE  = 'message';
        const ERRORLOG_LINE     = 'line';
        const ERRORLOG_FILE     = 'file';
        
        
       /**
        * Create a connection to the database
        * @param string $host is hostname of database
        * @param string $lgoin is username for login to the database
        * @param string $password is password for login to the database
        * @param string $db is selected database
        */
        private function __construct($host, $login, $password, $db) {
            self::$connection = mysql_connect($host, $login, $password) OR Diagnostics\ExceptionHandler::Exception('ERR_MYSQL_CONNECT');
            mysql_select_db($db, self::$connection);
            mysql_query('SET CHARACTER SET UTF-8');
        }
        
        
        /**
         * Singleton
         */
        public static function _init($host = FALSE, $login = FALSE, $password = FALSE, $db = FALSE) {
            if(self::$__instance == NULL) {
                if($host && $login && $password && $db) {
                    self::$__instance = new db($host, $login, $password, $db);
                }else return FALSE;
            }
            return self::$__instance;
        }


        /**
         * Parase array to sql input
         * @param string $parase type of parase (INSERT, COMMA, AND, NONE)
         * @param array $input input for db query
         * @return string is returned sql input
         */
        private static function array2SQL($parase = FALSE, array $input) {
            $return = $delimiter = FALSE;
            $out = array();
            
            foreach($input AS $param => $value) {
                $value = \inc\Security::protect($value, TRUE);
                $input[$param] = "'".$value."'";
            }

            switch($parase) {
                case 'INSERT' :
                    $out[] = '(';
                    $out[] = Arr::implodeArrayKeys($input, ',');
                    $out[] = ') VALUES (';
                    $out[] = implode(',', $input);
                    $out[] = ')';
                    $return = implode('', $out);
                break;
                case 'COMMA' :
                    $delimiter = ', ';
                break;
                case 'AND' :
                    $delimiter = ' AND ';
                break;
            }

            if(!$return) {
                foreach($input AS $param => $value) {
                    $out[] = $param.' = '.$value;
                }
                $return = implode($delimiter, $out);
            }
            return $return;
        }
        
        
        /**
         * This is 'all in 1' function
         * @param string $type type of mysql function
         * @param mixed $query MySQL query
         * @return mixed
         */
        public function q($type, $query) {
            $return = FALSE;
            
            switch($type) {
                case self::QUERY : 
                    $return = mysql_query($query, self::$connection); 
                break;
                case self::NUM_ROWS : 
                    $return = mysql_num_rows($query); 
                break;
                case self::FETCH_OBJECT : 
                    $return = mysql_fetch_object($query); 
                break;
                case self::FETCH_ARRAY : 
                    $result = mysql_fetch_array($query, MYSQL_ASSOC);
                    if(is_array($result)) $return = Arr::treatArrayValue($result);
                break;
            }
            return $return;
        }
        
        
        /**
         * This function is for work with MySQL database
         * @param string $type type of query
         * @param string $db param 1
         * @param string $column2 param 2
         * @param string $column3 param 3
         * @return mixed if $type is db::VIEW returned is string
         */
        public function uQuery($type, $db, $column2 = FALSE, $column3 = FALSE) { 
            $return = FALSE;
            switch($type) {
                case self::ADD : 
                    $query = 'INSERT INTO `'.$db.'` '.self::array2SQL('INSERT', $column2);
                    self::q(self::QUERY, $query);
                    $return = TRUE;
                break;
                case self::VIEWS :
                    if(!$column2){
                        $where = '1';
                    } elseif(is_numeric($column2)) {
                        $where = '`id` = "'.$column2.'"';
                    } elseif(is_array($column2)) {
                        $where = self::array2SQL('AND', $column2);
                    }
                    
                    $query = 'SELECT * FROM `'.$db.'` WHERE '.$where.' '.$column3;
                    $return = self::q(self::QUERY, $query);
                    
                    if(is_numeric($column2)) {
                        $return = self::q(self::FETCH_ARRAY, $return);
                    }
                break;
                case self::UPDATE : 
                    $query = 'UPDATE `'.$db.'` SET '.self::array2SQL('COMMA', $column2).' WHERE '.self::array2SQL('AND', $column3);
                    self::q(self::QUERY, $query);
                    $return = TRUE;
                break;
                case self::DELETE :
                    $query = 'DELETE FROM `'.$db.'` WHERE '.self::array2SQL('AND', $column2);
                    self::q(self::QUERY, $query);
                    $return = TRUE;
                break;
            }
            return $return;
        }
        
        
        /**
         * This is function for optimize table
         * @return array
         */
        public function optimizeTables() {
            $alltables = mysql_query("SHOW TABLES;");
            $output = array();
            
            while($table = mysql_fetch_assoc($alltables)) {
                foreach($table as $db => $tablename) {
                    $sql = 'OPTIMIZE TABLE '.$tablename.';';
                    $response = mysql_query($sql);
                    $output[] = mysql_fetch_assoc($response);
                };
            };
            return $output;
        }
    }