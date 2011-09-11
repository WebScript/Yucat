<?php
    /**
     * Base model for main functions for this system
     * 
     * @category   Yucat
     * @package    Model
     * @name       BaseModel
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace Model;
    
    class BaseModel {
        //Resource of DB connection
        private $db;
        
        public function __construct() {
            global $db;
            $this->db = $db; 
        }
        
        public function db() {
            return $this->db;
        }
        
        
        public function isLogged() {
        if(empty($_COOKIE['id']) || empty($_COOKIE['password'])) {
                return FALSE;
            }
            
            $result = $this->db()
                    ->tables('users')
                    ->where('id', UID)
                    ->where('password', $_COOKIE['password'])
                    ->limit(1)
                    ->fetch();
            if($result) {
                return $result;
            } else {
                return FALSE;
            }
        }
    }