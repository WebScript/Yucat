<?php
    /**
     * Base model for main functions for this system
     * 
     * @category   Yucat
     * @package    Model
     * @name       BaseModel
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace Model;
    
    class BaseModel {
        //Resource of DB connection
        private $db;
        
        public function __construct() {
            $this->db = $GLOBALS['db'];
        }
        
        
        
        protected function db() {
            return $this->db;
        }
        
        
        
        protected function isLogged() {
            GLOBAL $cookie;
           
            $uid = $this->db()->tables('cookie_params')
                    ->where('CID', $cookie->myCid)
                    ->where('param', 'UID')
                    ->fetch();

            return $uid ? $uid->value : NULL;
        }
    }