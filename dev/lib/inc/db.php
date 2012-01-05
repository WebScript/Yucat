<?php
    /**
     * This class manage all work with database
     *
     * @category   Yucat
     * @package    Library
     * @name       db (Database)
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.8.8
     * @link       http://www.yucat.net/documentation
     * 
     * @todo fix paramsReplace in exec()
     */

    namespace inc;
    
    use inc\Diagnostics\ExceptionHandler;

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
        /** @var SELECT * FROM ... WHERE 1 ORDER BY (var) */
        private $order;
        
               
        /**
         * Create a connection with DB and save to $this->connection
         * 
         * @param string $host Hostname of DB server
         * @param string $login Login of DB server
         * @param string $password Password of DB server
         * @param string $db Database with tables
         */
        public function __construct($host, $login, $password, $db) {
            $this->connection = mysql_connect($host, $login, $password);
            if(!$this->connection) new ExceptionHandler('Internal Server Error 500: Cannot connect to database!');
            $resp = mysql_select_db($db, $this->connection);
            if(!$resp) new ExceptionHandler('Internal Server Error 500: Cannot connect to database!');
        }
       
       
        /**
         * Parase array $input to SQL values and return it;
         * 
         * @param array $input SQl data
         * @param string $delimiter 
         * @param BOOL $setter Setters is using if you insertting data to tables
         * @return string parsed sql data
         */
        private function parse(array $input, $delimiter, $setter = TRUE) {           
            foreach($input AS $key => $val) {
                if(is_array($val)) {
                    if(!isset($val[0])) {
                        new ExceptionHandler('Internal Server Error 500: Cannot write array to database!');
                    } else {
                        $input[$key] = \inc\Security::protect($val[0], TRUE);
                    }
                } else {
                    $val = \inc\Security::protect($val, TRUE);
                    $input[$key] = "'" . $val . "'";
                }
            }

            if($delimiter === 'INSERT') {
                $return = '(' . Arr::implodeArrayKeys($input, ',');
                $return .= ') VALUES (';
                $return .= implode(',', $input) . ')';
                $delimiter = '';
            } else {
                $return = '';
                foreach($input AS $param => $value) {
                    $return .= $setter ? $param . ' = ' . $value : $value;
                }
            }
            return $return;
        }
        
        
        /**
         * Make SQL query from class variables
         * 
         * @return string SQL query
         */
        public final function make() {           
            if($this->action === 'SELECT') {
                $query = 'SELECT ';
                $query .= $this->select;
                $query .= ' FROM ';
                $query .= $this->tables;
                $query .= ' WHERE ';
                $query .= $this->where ? $this->parse($this->where, ' AND ') : '1';
                
                if($this->order !== NULL) {
                    $query .= ' ORDER BY ';
                    $query .= $this->order;
                }
                
                if($this->limit !== NULL) {
                    $query .= ' LIMIT ';
                    $query .= $this->limit;
                    
                    if($this->offset !== NULL) {
                        $query .= ', ';
                        $query .= $this->offset;
                    }
                }
            } elseif($this->action === 'INSERT') {
                $query = 'INSERT INTO ';
                $query .= $this->tables . ' ';
                $query .= $this->parse($this->values, 'INSERT', FALSE);
            } elseif($this->action === 'UPDATE') {
                $query = 'UPDATE ';
                $query .= $this->tables;
                $query .= ' SET ';
                $query .= $this->parse($this->values, ',');
                $query .= ' WHERE ';
                $query .= $this->parse($this->where, ' AND ');
            } elseif($this->action === 'DELETE') {
                $query = 'DELETE FROM ';
                $query .= $this->tables;
                $query .= ' WHERE ';
                $query .= $this->parse($this->where, ' AND ');
                
                if($this->limit !== NULL) {
                    $query .= ' LIMIT ';
                    $query .= $this->limit;
                    
                    if($this->offset !== NULL) {
                        $query .= ', ';
                        $query .= $this->offset;
                    }
                }
            }
            
            $this->query = $query;
            return $query;
        }
        
        
        /**
         * This must be first called method in db query becasue have function 
         * for clean all class variables.
         * And this method selecting tables for work with it.
         * 
         * @param string $table users, servers, machines, etc.
         * @return db resource of this class
         */
        public final function tables($table) {
            $this->clean();
            $this->tables = \inc\Security::protect($table, TRUE);
            return $this;
        }
        
        
        /**
         * This method determine under with parameters is command called
         * 
         * @param string $what SQL column
         * @param string $by any data
         * @param BOOL determine is second parameter is column or any data
         * @return db resource of this class
         */
        public final function where($what, $by, $sql = FALSE) {
            if($sql) {
                $this->where = array_merge($this->where, array($what => array($by)));
            } else {
                $this->where = array_merge($this->where, array($what => $by));
            }
            return $this;
        }
        
        
        /**
         * Insert values to DB
         * 
         * @param array $input all insert values name => value
         * @return result
         */
        public final function insert(array $input) {
            $this->action = 'INSERT';
            $this->values = array_merge($this->values, $input);
            return $this->exec($this->make());
        }
        
        
        /**
         * Method for delete table, use with where() and tables()
         * 
         * @return db result
         */
        public final function delete() {
            $this->action = 'DELETE';
            return $this->exec($this->make());
        }
        
        
        /**
         * Update DB
         * 
         * @param array $what update by name => value
         * @return result
         */
        public final function update(array $what) {
            $this->action = 'UPDATE';
            $this->values = $what;
            return $this->exec($this->make());
        }

        
        /**
         * Select select() from DB e.g. SELECT $input FROM ...
         * 
         * @param string $input select
         * @return db resource of this class
         */
        public final function select($input) {
            $this->select = \inc\Security::protect($input);
            return $this;
        }
        
        
        /**
         * Set order to SQL request
         * 
         * @param string $by order e.g. id DESC
         * @return db reource of this class
         */
        public final function order($by) {
            $this->order = \inc\Security::protect($by);
            return $this;
        }


        /**
         * Set rows limit
         * 
         * @param integer $limit Limit
         * @param integer $offset Offset
         * @return db resource of this class
         */
        public final function limit($limit, $offset = NULL) {
            $this->limit = $limit;
            $this->offset = $offset;
            return $this;
        }
        
        
        /**
         * alias for mysql_query
         * You can use exec('SELECT * FROM users WHERE id = 7 AND lock = 0') - not secured input
         * OR exec('SELECT * FROM users WHERE id = ? AND lock = ?', 7, 0) - secured input
         * 
         * @param string $input SQL command
         * @return mysql result
         */
        public final function exec($input) {
            //$input = String::paramsReplace(func_get_args());
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
         * @return array all mysql result
         */
        public final function fetchAll() {
           $out = array();
           $result = $this->exec($this->make());
           
           if(!$result) {
               new Diagnostics\ExceptionHandler($this->query);
           } else {
               while($row = mysql_fetch_object($result)) {
                   $out[] = $row;
               }
           }
           
           return $out;
       }
       
       
       /**
         * Create a object of DB query
        * 
         * @return object mysql result
         */
        public final function fetch() {
            $result = $this->exec($this->make());
            
            if(!$result) {
                new Diagnostics\ExceptionHandler($this->query);
            } else {
                $result = mysql_fetch_object($result);
            }
            
            return $result;
        }
       
       
        /**
         * Get count of rows
         * 
         * @return integer count of rows
         */
        public final function numRows() {
            $result = $this->exec($this->make());
            return mysql_num_rows($result);
        }
        
        
        /**
         * This is private function for clean vars of class
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
        
        
        /**
         * Close mysql connection
         */
        public function __destruct() {
            @mysql_close($this->connection);
        }
    }