<?php
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
     * 
     * 
     * @todo Dorobit dokumentaciu
     * @todo dorobit make() myslim
     * @todo spravit dump()
     * @todo fixnut clear()
     */

    namespace inc;



    class db {
        /** @var ressource of connection to DB */
        private $connection;
        /** @var SQL query */
        private $query;
        /** @var name tables with is used */
        private $tables;
        /** @var main action, INSERT, UPDATE, DELETE, SELECT */
        private $action = 'SELECT'; 
        /** @var select in table by this e.g. SELECT * FROM ... WHERE (var) */
        private $where = array();
        /** @var array of update or insert values */
        private $values = array();
        /** @var select in SELECT (var) FROM */
        private $select;
        /** @var limit of rows */
        private $limit;
        /** @var offset */
        private $offset;
        
        
        /**
         * Select table for actions with DB 
         * @param string $table users, servers, machines, etc.
         * @return db 
         */
        public function tables($table) {
            $this->clear();
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
        
        
        public function insert(array $input) {
            $this->action = 'INSERT';
            $this->values = array_merge($this->values, $input);
            return $this;
        }
        
        
        public function delete() {
            $this->action = 'DELETE';
            return $this;
        }
        
        
        public function update(array $what) {
            $this->action = 'UPDATE';
            $this->values = $what;
        }

        
        public function select($input) {
            $this->select = $input;
            return $this;
        }



        public function limit($limit, $offset = NULL) {
            if(is_numeric($limit) && is_numeric($offset)) {
                $this->limit = $limit;
                $this->offset = $offset;
            } else {
                Diagnostics\ExceptionHandler::Exception('ERR_NOT_NUMERIC');
            }
            return $this;
        }
        
        
        public function query() {
            return mysql_query($this->make());
        }
        
        
        public function exec($input) {
            return mysql_query($input);
        }
        
        


       public function fetch() {
           $out = array();
           $result = $this->query();
           
           while($row = mysql_fetch_object($result)) {
               $out[] = $row;
           }
           
           return $out;
       }
        
        
        /*
         * insert
         * update
         * where
         */
        
        
        
        
        
        private function clear() {
            $this->tables = NULL;
            $this->where = array();
            $this->insert = array();
            $this->limit = NULL;
            $this->offset = NULL;
        }
        
      
        
        

        public function __construct($host, $login, $password, $db) {
            $this->connection = mysql_connect($host, $login, $password) OR Diagnostics\ExceptionHandler::Exception('ERR_MYSQL_CONNECT');
            mysql_select_db($db, $this->connection);
            mysql_query('SET CHARACTER SET UTF-8');
        }
       
       
        
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
         * @todo dorobyt, zostroji sql query podla globalnych premenych v classe
         */
        public function make() {
            // SELECT * FROM users WHERE id = 7 LIMIT 1, 30
            $query = array();
            
            if($this->action === 'SELECT') {
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
            } elseif($this->action === 'INSERT') {
                $query[] = 'INSERT INTO ';
                $query[] = $this->tables;
                $query[] = $this->parse($this->values, 'INSERT');
            } elseif($this->action === 'UPDATE') {
                $query[] = 'UPDATE ';
                $query[] = $this->tables;
                $query[] = ' SET ';
                $query[] = $this->parse($this->where, ',', TRUE);
                $query[] = ' WHERE ';
                $query[] = $this->parse($this->where, ' AND ', TRUE);
            } elseif($this->action === 'DELETE') {
                $query[] = 'DELETE FROM ';
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
            }
            
            $query = implode('', $query);
            $this->query = $query;
            return $query;
        }

    }