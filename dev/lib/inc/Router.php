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
     * @version    Release: 0.2.7
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc;
    
    class Router {
       
        /**
         * With this function you can get $_GET and parse it
         * @return array
         */
        public static function getAddress() {
            $out = array();
            
            if(isset($_GET['uf'])) {
                $route = explode('/', $_GET['uf']);
            } elseif(isset($_GET)) {
                $route = $_GET;
            }
            $i = 0;
                
            foreach($route as $val) {
                $a = '/Presenter/' . implode('/', $out);
                $dir = ROOT . $a . '/' . $val;
            
                if(is_dir($dir)) {
                    $out['dir'] = $val;
                } elseif(file_exists($dir . '.php')) {
                    $out['class'] = $val;
                } elseif(method_exists(str_replace('/', '\\', $a), $val)) {
                    $out['method'] = $val;
                } else {
                    $out['key' . $i] = $val;
                    $i++;
                }
            }
            
            if(empty($out['class'])) {
                $out = array();
                $out['class'] = 'Login';
            }
            
            $out['dir'] = isset($out['dir']) ? $out['dir'] : NULL;
            $out['class'] = isset($out['class']) ? $out['class'] : NULL;
            $out['method'] = isset($out['method']) ? $out['method'] : NULL;

            return $out;
        }
        
        
        public static function getLevel() {
            $addr = self::getAddress();

            if(isset($addr['class']) && isset($addr['method'])) {
                return 3;
            } elseif(isset($addr['dir']) && isset($addr['class'])) {
                return 2;
            } elseif(isset($addr['class'])) {
                return 1;
            } else {
                return FALSE;
            }
        }
        
        
        public static function getOnlyParam() {
            $addr = self::getAddress();
            
            for($i=0;$i<self::getLevel();$i++) {
                unset($addr[array_search(reset($addr), $addr)]);
            }
            return $addr;
        }
        
        
        public static function getOnlyAddress() {
            $addr = self::getAddress();
            $out = array();
            
            for($i=0;$i<self::getLevel();$i++) {
                $out[array_search(reset($addr), $addr)] = $addr[array_search(reset($addr), $addr)];
                unset($addr[array_search(reset($addr), $addr)]);
            }
            return $out;
        }
        
        
        /**
         * This function is for create a real URL
         * You can use e.g. 'User:Profile:ajaxGetForm password,auth_key' and his call class Profile
         * in dir User and method ajaxGetForm with arguments password and auth_key as array
         * @param string $input
         * @return string
         */
        public static function traceRoute($input) {
            if(!is_array($input)) {
                //Parse to call and arguments
                $input = explode(' ', $input, 2);
                //Parse to simple called method, class and dir
                $path = explode(':', $input[0]);

                if(isset($input[1])) {
                    $p = explode(',', $input[1]);
                    $path = array_merge($path, $p);
                }
                $vals = '/' . implode('/', array_filter($path));
            } else {
                $vals = '/' . implode('/', $input);
            }
            
            return $GLOBALS['conf']['protocol']
                 . DOMAIN
                 . $vals;
        }

        
        /**
         * Redirect to web by special syntax for traceRoute
         * @param string $input 
         */
        public static function redirect($input, $inURL = FALSE) {
            $search = self::traceRoute($input);
            
            if(!preg_match('@' . $search . '@', $_SERVER['SCRIPT_URI']) && $inURL || !$inURL) {
                if(Ajax::isAjax()) {
                    exit('{"redirect" : "' . self::traceRoute($input) . '"}');
                } else {
                    header('location: ' . self::traceRoute($input));
                }    
            }
        }
        
        
        /*
         * Vnutorny redirect, pouziva sa ked je zadana napr URL User/Statistic a chcete to presmerovat na User/Statistic/statistic
         */
        public static function like($input) {
            if(is_array($input)) { 
                $input = implode(':', $input);
            }
            $search = substr($input, 0, strrpos($input, ':'));
            $search = self::traceRoute($search);
            
            if(!preg_match('@' . $search . '/(.*)@', $_SERVER['SCRIPT_URI'])) {
                self::redirect($input);
            }
        }
    }