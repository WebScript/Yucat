<?php

    namespace inc;
    
    /**
     * This is main class for work with page system and template system.
     * This class getting and parsing URL and call the relevant method and class
     *
     * @category   Yucat
     * @package    Includes
     * @name       Router
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Yucat Technologies (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     * @deprecated Class deprecated in Release 0.0.0
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
         * You can use e.g. 'User:Profile password' and his call class User
         * method Profile with arguments password as array
         * @param string $input
         * @return string
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
         */
        public static function callRoute() {
            $get = self::getAddress();
            
            if(isset($get[0])) {
                $get[0] = '\\Model\\'.$get[0];
                
                if(method_exists($get[0], $get[1])) {
                    if($get[2]) {
                        $get[0]::$get[1]($get[2]);
                    } else { 
                        $get[0]::$get[1]();
                    }
                } elseif(method_exists($get[0], 'defaultMethod')) {
                    $get[0]::defaultMethod();
                } else Diagnostics\ExceptionHandler::Exception ('PAGE_NOT_FOUND');
            }
        } 
    }