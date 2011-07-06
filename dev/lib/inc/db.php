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
        /** @var ressource of connection to DB */
        private static $connection;
        
        
        
        
       /**
        * Create a connection to the database
        * @param string $host is hostname of database
        * @param string $lgoin is username for login to the database
        * @param string $password is password for login to the database
        * @param string $db is selected database
        */
        public function __construct($host, $login, $password, $db) {
            self::$connection = mysql_connect($host, $login, $password) OR Diagnostics\ExceptionHandler::Exception('ERR_MYSQL_CONNECT');
            mysql_select_db($db, self::$connection);
            mysql_query('SET CHARACTER SET UTF-8');
        }
        
        
        private $tables = array();
        private $limit;
        private $offset;
        private $where = array();
        
        
        
        
       
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