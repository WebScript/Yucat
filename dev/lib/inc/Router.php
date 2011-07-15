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
     * @version    Release: 0.1.0
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
            return array_filter($out);
        }
        
        
        /**
         * This function is for create a real URL
         * You can use e.g. 'User:Profile:ajaxGetForm password,auth_key' and his call class Profile
         * in dir User and method Profile with arguments password and auth_key as array
         * @param string $input
         * @return string
         * 
         * @todo opravit klasicke smerovanie
         */
        public static function traceRoute($input) {
            //Parse to call and arguments
            $input = explode(' ', $input, 2);
            //Parse to simple called method, class and dir
            $path = explode(':', $input[0], 3);
            
            if(isset($input[1])) {
                $p = explode(',', $input[1]);
                $path = array_merge($path, $p);
            }
            
            return CFG_PROTOCOL.CFG_WEBSITE
                 . (self::$userFriendly ? '/' : '/?param=')
                 . implode((self::$userFriendly ? '/' : '&param='), $path);
        }
        
        
        /**
         * This function is Head function for call and use page system
         * By URL you can call method
         * if BOOL is FALSE = Diagnostics\ExceptionHandler::Exception ('PAGE_NOT_FOUND');
         * @return BOOL
         */
        public function callPresenter() {
            $get = self::getAddress();
            
            if(isset($get[0])) {
                $class = '\\Model\\' . $get[0] . '\\' . $get[1];
                $method = $get[2];
                
                unset($get[0]);
                unset($get[1]);
                unset($get[2]);
                
                if(class_exists($class)) {
                    $class = new $class;
                } else {
                    return FALSE;
                }
                
                if(method_exists($class, $method)) {
                    call_user_func_array(array($class, $method), $get);
                } elseif(method_exists($class, 'default')) {
                    call_user_func_array(array($class, 'default'));
                } else {
                    return FALSE;
                }
            }
        } 
    }