<?php
    /**
     * This is class for connect, manage and database.
     *
     * @category   Yucat
     * @package    Includes
     * @name       db
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.8.6
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.3.2
     */

    namespace inc;

    class db {
        /** @var ressource of connection to DB */
        private $connection;
        /** @var SQL query */
        public $query;
        /** @var name tables with is used */
        private $tables;
        /** @var main action, INSERT, UPDATE, DELETE, SELECT */
        private $action = 'SELECT'; 
        /** @var select in table by this e.g. SELECT * FROM ... WHERE (var) */
        private $where = array();
        /** @var array of update or insert values */
        private $values = array();
        /** @var select in SELECT (var) FROM */
        private $select = '*';
        /** @var limit of rows */
        private $limit;
        /** @var offset */
        private $offset;
        
        private $order;
        
               
        /**
         * Create a connection with DB
         * @param string $host
         * @param string $login
         * @param string $password
         * @param string $db 
         */
        public function __construct($host, $login, $password, $db) {
            $this->connection = mysql_connect($host, $login, $password);            
            mysql_select_db($db, $this->connection);
        }
       
       
        /**
         * Parase array to SQL values
         * @param array $input
         * @param string $delimiter
         * @param BOOL $setter
         * @return string 
         */
        private function parse(array $input, $delimiter, $setter = FALSE) {
            $out = array();
            
            foreach($input AS $key => $val) {
                $val = \inc\Security::protect($val, TRUE);
                $input[$key] = "'" . $val . "'";
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

            if(empty($return)) {
                foreach($input AS $param => $value) {
                    $out[] = $setter ? $param . ' = ' . $value : $value;
                }
                $return = implode($delimiter, $out);
            }
            return $return;
        }
        
        
        /**
         * Make a SQL query from vlass variables
         * @return string
         */
        public function make() {
            $query = array();
            
            if($this->action === 'SELECT') {
                $query[] = 'SELECT ';
                $query[] = $this->select;
                $query[] = ' FROM ';
                $query[] = $this->tables;
                $query[] = ' WHERE ';
                $query[] = $this->where ? $this->parse($this->where, ' AND ', TRUE) : '1';
                
                if($this->order !== NULL) {
                    $query[] = ' ORDER BY ';
                    $query[] = $this->order;
                }
                
                if($this->limit !== NULL) {
                    $query[] = ' LIMIT ';
                    $query[] = $this->limit;
                    
                    if($this->offset !== NULL) {
                        $query[] = ', ';
                        $query[] = $this->offset;
                        
                    }
                }
            } elseif($this->action === 'INSERT') {
                $query[] = 'INSERT INTO ';
                $query[] = $this->tables . ' ';
                $query[] = $this->parse($this->values, 'INSERT');
            } elseif($this->action === 'UPDATE') {
                $query[] = 'UPDATE ';
                $query[] = $this->tables;
                $query[] = ' SET ';
                $query[] = $this->parse($this->values, ',', TRUE);
                $query[] = ' WHERE ';
                $query[] = $this->parse($this->where, ' AND ', TRUE);
            } elseif($this->action === 'DELETE') {
                $query[] = 'DELETE FROM ';
                $query[] = $this->tables;
                $query[] = ' WHERE ';
                $query[] = $this->parse($this->where, ' AND ', TRUE);
                
                if($this->limit !== NULL) {
                    $query[] = ' LIMIT ';
                    $query[] = $this->limit;
                    
                    if($this->offset !== NULL) {
                        $query[] = ', ';
                        $query[] = $this->offset;
                        
                    }
                }
            }
            
            $query = implode('', $query);
            $this->query = $query;
            //d($query);
            return $query;
        }
        
        
        /**
         * Select table for actions with DB 
         * @param string $table users, servers, machines, etc.
         * @return db 
         */
        public function tables($table) {
            $this->clean();
            $this->tables = \inc\Security::protect($table, TRUE);
            return $this;
        }
        
        
        /**
         * Set where for get data from DB
         * @param string $what
         * @param string $by
         * @return db 
         */
        public function where($what, $by) {
            $this->where = array_merge($this->where, array($what => $by));
            return $this;
        }
        
        
        /**
         * Insert values to DB
         * @param array $input
         * @return db 
         */
        public function insert(array $input) {
            $this->action = 'INSERT';
            $this->values = array_merge($this->values, $input);
            $this->exec($this->make());
        }
        
        
        /**
         * If you can delete table use this with table() and where()
         * @return db 
         */
        public function delete() {
            $this->action = 'DELETE';
            return $this->exec($this->make());
        }
        
        
        /**
         * Update DB
         * @param array $what 
         */
        public function update(array $what) {
            $this->action = 'UPDATE';
            $this->values = $what;
            return $this->exec($this->make());
        }

        
        /**
         * Select select() from DB e.g. SELECT $input FROM ...
         * @param string $input
         * @return db 
         */
        public function select($input) {
            $this->select = \inc\Security::protect($input);
            return $this;
        }
        
        
        public function order($by) {
            $this->order = \inc\Security::protect($by);
            return $this;
        }


        /**
         * Set rows limit
         * @param integer $limit
         * @param integer $offset
         * @return db 
         */
        public function limit($limit, $offset = NULL) {
            $this->limit = $limit;
            $this->offset = $offset;
            return $this;
        }
        
        
        /**
         * alias for mysql_query
         * You can use exec('SELECT * FROM users WHERE id = 7 AND lock = 0') - not secured input
         * OR exec('SELECT * FROM users WHERE id = ? AND lock = ?', 7, 0) - secured input
         * @param string $input
         * @return mixed 
         */
        public function exec($input) {
            $input = String::paramsReplace(func_get_args());
            return mysql_query($input);
        }
        
        
        /**
         * Create a array of objects of DB query
         * e.g.
         * Array (
         * [0] => ->name
         *        ->password
         * [1] => ->name
         *        ->password
         * )
         * 
         * @return array
         */
        public function fetchAll() {
           $out = array();
           $result = $this->exec($this->make());
           
           if(!$result) {
               //d($this->query);
               Diagnostics\ExceptionHandler::Error($this->query);
           } else {
               while($row = mysql_fetch_object($result)) {
                   $out[] = $row;
               }
           }
           
           return $out;
       }
       
       
       /**
         * Create a object of DB query
         * @return object
         */
        public function fetch() {
           $result = $this->exec($this->make());
           return /*$result === FALSE ? FALSE :*/ mysql_fetch_object($result);
       }
       
       
       public function numRows() {
           $result = $this->exec($this->make());
           return mysql_num_rows($result);
       }
        
        
        /**
         * This is private function for clean fars of class
         */
        private function clean() {
            $this->tables = NULL;
            $this->action = 'SELECT';
            $this->where = array();
            $this->values = array();
            $this->select = '*';
            $this->limit = NULL;
            $this->offset = NULL;
            $this->order = NULL;
        }
        
        
        public function __destruct() {
            @mysql_close($this->connection);
        }
    }