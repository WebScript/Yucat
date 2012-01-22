<?php
    /**
     * This is main class for work with page system and template system.
     * This class getting and parsing URL and call the relevant method and class
     *
     * @category   Yucat
     * @package    Library
     * @name       Router
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.4.1
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;
    
    use inc\Diagnostics\Excp;
    
    class Router {
        /** @var Router instance of this class */
        private static $singleton;
        /** @var array Addrees */
        private $address    = array(
            'subdomain' => '', 
            'dir' => array(),
            'class' => ''
            );
        
        const DELIMITER = ':';
        
        
        /**
         * Parse URL and create varialble with all important data
         */
        public final function __construct() {
            /* Use singleton */
            if(!self::$singleton) {
                /* Get domain, e.g. admin, mobile, etc. */
                list($subDomain) = explode('.', DOMAIN);
                /* parse domain path to array */
                $route = explode('/', ROUTE);
                /* set subdomain to variable address */
                $this->address['subdomain'] = is_dir(ROOT . PRESENTER . $subDomain) ? $subDomain : 'website';
                /* Set dir to presenter */
                $dir = PRESENTER . $this->address['subdomain'] . '/';
                /* counter */
                $i = 0;

                /* Set dir names */
                while(1) {
                   if(count($route) > $i && !empty($route[$i]) && is_dir(ROOT . $dir . $route[$i])) {
                        $this->address['dir']['d' . $i] = $route[$i];
                        $dir .= $route[$i] . '/';
                        $i++;
                    } else {
                        break;
                    }
                }

                $cDir = str_replace('/', '\\', $dir);

                /** Set class */
                if(count($route) > $i && class_exists($cDir . $route[$i])) {
                    $this->address['class'] = $route[$i];
                } else {
                    if(class_exists($cDir . 'Index')) {
                        $this->address['class'] = 'Index';
                    } else {
                        new Excp('E_ISE', 'E_CLASS_NO_EXISTS');
                    }
                }
                $i++;

                /** Set method */
                if(count($route) > $i && method_exists($cDir . $this->address['class'], $route[$i])) {
                    $this->address['method'] = $route[$i];
                    $i++;
                }

                if(count($route) > $i) {
                    $this->address['params'] = array_slice($route, $i);
                } 
            }
            
            self::$singleton = $this;
        }
        
        
        /**
         * Singleton
         * 
         * @return Config isntance
         */
        public static function _init() {
            if(!self::$singleton) return new Router();
            return self::$singleton;
        }

       
        /**
         * Return all parsed data
         * 
         * @return array All data
         */
        public final function getAddress() {
            return $this->address;
        }
        
        
        /**
         * Return parameter by $param
         * 
         * @param string $param parameter
         * @return string
         */
        public final function getParam($param) {
            if(array_key_exists($param, $this->address)) {
                return $this->address[$param];
            } else {
                return NULL;
            }
        }
        
       
        /**
         * This function is for create a real URL
         * You can use e.g. 'User:Profile:ajaxGetForm password,auth_key' and his call class Profile
         * in dir User and method ajaxGetForm with arguments password and auth_key as array
         * 
         * @param string $input string with address
         * @return string URL
         */
        public static function traceRoute($input) {
            $out = PROTOCOL;
            
            if(!is_array($input)) {
                $input = explode(self::DELIMITER, $input);
            }
            
            if($input[0] && $input[0] == 'www' || $input[0] && is_dir(ROOT . PRESENTER . $input[0])) {
                $subDomain = $input[0];
                unset($input[0]);
                
                if(strpos(DOMAIN, $subDomain) !== FALSE) { 
                    $subDomain = '';
                }
                
                $out .= $subDomain;
            }
            
            return $out
            . DOMAIN
            . '/'
            . implode('/', $input);
        }
        
        
        /**
         * Parse link from admin:User:Main:profile to array
         * 
         * @param string $link url
         * @return array Parsed url
         */
        private static function parseRoute($link) {
            /* initialize output */
            $out = array();
            /* Parse link path to array */
            $link = explode(self::DELIMITER, $link);
            /* set subdomain to variable address */
            if(is_dir(ROOT . PRESENTER . $link[0])) {
                $out['subdomain'] = $link[0];
                $i = 1;
            } else {
                list($out['subdomain']) = explode('.', DOMAIN);
                $i = 0;
            }
            /* Set presenter's dir */
            $dir = PRESENTER . $out['subdomain'] . '/';

            /** Set dir names */
            while(1) {
               if(count($link) >= $i + 1 && is_dir(ROOT . $dir . $link[$i])) {
                    $out['dir']['d' . $i] = $link[$i];
                    $dir .= $link[$i] . '/';
                    $i++;
                } else {
                    break;
                }
            }
            
            
            $cDir = str_replace('/', '\\', $dir);

            /** Set class */
            if(count($link) > $i && class_exists($cDir . $link[$i])) {
                $out['class'] = $link[$i];
            } else {
                if(class_exists($cDir . 'Index')) {
                    $out['class'] = 'Index';
                } else {
                    new Excp('E_ISE', 'E_CLASS_NO_EXISTS');
                }
            }
            $i++;

            /** Set method */
            if(count($link) > $i && method_exists($cDir . $out['class'], $link[$i])) {
                $out['method'] = $link[$i];
                $i++;
            }
            
            if(count($link) > $i) {
                $out['params'] = array_slice($link, $i);
            }
            return $out;
        }

        
        /**
         * Redirect to web by special syntax for traceRoute
         * 
         * @param string $input 
         */
        public static function redirect($input, $inURL = FALSE) {
            $parse = self::parseRoute($input);
            $parse = $parse['subdomain'] . ':' . implode(':', $parse['dir']) . ':' . $parse['class'];
            $parse = self::traceRoute($parse);
            $search = self::traceRoute($input);

            if($inURL && preg_match('@' . $parse . '$@i', $_SERVER['SCRIPT_URI']) || !$inURL) {
                if(Ajax::isAjax()) {
                    exit('{"redirect" : "' . self::traceRoute($input) . '"}');
                } else {
                    header('location: ' . self::traceRoute($input));
                }    
            }
        }
        
       
        /**
         * get domain without subdomain
         * 
         * @return string
         */
        public static function getDomain() {
            $domain = array_reverse(explode('.', DOMAIN));
            return $domain[1] . '.' . $domain[0];
                    
        }
        
        
        /**
         * get link from url array
         *  
         * @return string
         */
        public final function getLink() {
            return $this->address['subdomain'] . ':' . implode(':', $this->address['dir']) . ':' . $this->address['class'] . (isset($this->address['method']) ? ':' . $this->address['method'] : '');
        }
    }