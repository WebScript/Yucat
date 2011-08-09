<?php
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
     * @version    Release: 0.2.5
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc;
    
    class Router {
        
        /** Is address user friendly? */
        private static $userFriendly = 1;
      
        
        /**
         * With this function you can get $_GET and parse it
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
            
            if(!isset($out[0]) && !isset($out[1])) {
                $out[0] = 'Login';
            } 
            return array_filter($out);
        }
        
        
        /**
         * This function is for create a real URL
         * You can use e.g. 'User:Profile:ajaxGetForm password,auth_key' and his call class Profile
         * in dir User and method ajaxGetForm with arguments password and auth_key as array
         * @param string $input
         * @return string
         * 
         * @todo opravit klasicke smerovanie
         */
        public static function traceRoute($input) {
          
            /*//Parse to call and arguments
            $input = explode(' ', $input, 2);
            //Parse to simple called method, class and dir
            $path = explode(':', $input[0], 3);
            
            if(isset($input[1])) {
                $p = explode(',', $input[1]);
                $path = array_merge($path, $p);
            }
            
            $path = array_filter($path);
            
            return CFG_PROTOCOL 
                 . DOMAIN
                 . (self::$userFriendly ? '/' : '/?param=')
                 . implode((self::$userFriendly ? '/' : '&param='), $path);*/
            $input = str_replace(':', '/', $input);
            return CFG_PROTOCOL 
                 . DOMAIN
                 . '/' 
                 . $input;
        }

        
        /**
         * Redirect to web by special syntax for traceRoute
         * @param string $input 
         */
        public static function redirect($input, $class = FALSE) {
            if($class) {
                $search = substr($input, 0, strrpos($input, ':'));
                $search = self::traceRoute($search . '/(.*)');
            } else {
                $search = self::traceRoute($input);
            }
            
            if(!preg_match('@' . $search . '@', $_SERVER['SCRIPT_URI'])) {
                header('location: ' . self::traceRoute($input));
            }
        }
    }