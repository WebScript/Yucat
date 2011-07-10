<?php

    namespace inc;
    
    /**
     * This is main class for work with page system and template system.
     * This class getting and parsing URL and call the relevant method and class
     *
     * @category   Yucat
     * @package    Includes
     * @name       Router
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */
    
    class Router {
        
        /** Is address user friendly? */
        private static $userFriendly = 1;
      
        
        /**
         * With this function you can get $_GET and parase it
         * @return array
         */
        public static function getAddress() {
            $out = array();
            
            if(isset($_GET['uf'])) {
                $out = explode('/', $_GET['uf']);
            } elseif(isset($_GET)) {
                foreach($_GET as $value) {
                    $out[] = $value;
                }
            }
            return array_filter($out);
        }
        
        
        /**
         * This function is for parse and create URL
         * You can use e.g. 'User:Profile password,auth_key' and his call class User
         * method Profile with arguments password and auth_key as array
         * @param string $input
         * @return string
         * 
         * @todo opravit klasicke smerovanie
         */
        public static function traceRoute($input) {
            $input = explode(' ', $input, 2);
            $path = array();
            
            $called = explode(':', $input[0], 2);
            $path = array_merge($path, $called);
            
            if(isset($input[1])) {
                $p = explode(',', $input[1]);
                $path = array_merge($path, $p);
            }
            
            return CFG_PROTOCOL.CFG_WEBSITE.(self::$userFriendly ? '/' : '/?param=').implode((self::$userFriendly ? '/' : '&param='), $path);
        }
        
        
        /**
         * This function is Head function for call and use page system
         * By URL you can call method
         * if BOOL is FALSE = Diagnostics\ExceptionHandler::Exception ('PAGE_NOT_FOUND');
         * @return BOOL
         * 
         * @prerobit aby tato funkcia predavala parametre sablonovaciemu systemu a ten volal presenter!
         *  
         */
        public function callPresenter() {
            $get = self::getAddress();
            
            if(isset($get[0])) {
                $class = '\\Model\\'.$get[0];
                $method = $get[1];
                
                unset($get[0]);
                unset($get[1]);
                
                if(class_exists($class)) {
                    $class = new $class;
                } return FALSE;
                
                if(empty($class)) {
                    call_user_func_array(array($class, 'default'));
                } elseif(method_exists($class, $get[1])) {
                    call_user_func_array(array($class, $get[1]), $get);
                } else Diagnostics\ExceptionHandler::Exception ('PAGE_NOT_FOUND');
            }
        } 
    }