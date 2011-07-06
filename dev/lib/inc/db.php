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
        private $connection;
        private $query;
        
        private $tables;
        
        
        private $action; 
        private $where = array();
       
        private $select;
        
        private $limit;
        private $offset;
        
        
        public function table($table) {
            $this->clear();
            $this->tables = $table;
            return $this;
        }
        
        
        public function where($what, $by) {
            $this->where = array_merge($this->where, array($what => $by));
            return $this;
        }
        
        
        public function insert(array $input) {
            $this->insert = array_merge($this->insert, $input);
            return $this;
        }
        
        
        public function delete($what, $by) {
            $this->delete = array_merge($this->delete, array($what => $by));
            return $this;
        }

        
        public function select($input) {
            $this->select = $input;
            return $this;
        }



        public function limit($limit, $offset = NULL) {
            $this->limit = $limit;
            $this->offset = $offset;
            return $this;
        }
        
        
        public function query($query) {
            return mysql_query($query);
        }
        
        
        public function make() {
            // SELECT * FROM users WHERE id = 7 LIMIT 1, 30
            $query = array();
            
            if($this->where) {
                $query[] = 'SELECT ';
                $query[] = $this->select ? $this->select : '*';
                $query[] = ' FROM ';
                $query[] = $this->tables;
                $query[] = ' WHERE ';
                $query[] = $this->parse($this->where, ' AND ', TRUE);
                
                if($this->limit) {
                    $query[] = ' LIMIT ';
                    $query[] = $this->limit;
                    
                    if($this->offset) {
                        $query[] = ', ';
                        $query[] = $this->offset;
                        
                    }
                }
            } elseif($this->insert) {
                
            } elseif($this->delete) {
                
            } elseif($this->update) {
                
            }
            
            $query = implode('', $query);
            $this->query = $query;
            return $query;
        }






       /* public function fetch() {
            $
        }
        */
        
        
        
        
        
        
        
        
        
        
        
        
        private function clear() {
            $this->tables = NULL;
            $this->where = array();
            $this->insert = array();
            $this->limit = NULL;
            $this->offset = NULL;
        }
        
        
        
        
        
        
        
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
        
        

        
       
        /**
         * Parase array to sql input
         * @param string $parase type of parase (INSERT, COMMA, AND, NONE)
         * @param array $input input for db query
         * @return string is returned sql input
         */
        private function parse(array $input, $delimiter, $setter = FALSE) {
            $return = FALSE;
            $out = array();
            
            foreach($input AS $param => $value) {
                $value = \inc\Security::protect($value, TRUE);
                $input[$param] = "'".$value."'";
            }

            if($delimiter === 'INSERT') {
                $out[] = '(';
                $out[] = Arr::implodeArrayKeys($input, ',');
                $out[] = ') VALUES (';
                $out[] = implode(',', $input);
                $out[] = ')';
                $return = implode('', $out);
                $delimiter = '';
            }

            if(!$return) {
                foreach($input AS $param => $value) {
                    $out[] = $setter ? $param.' = '.$value : $value;
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