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
     * @version    Release: 0.3.5
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;
    
    use inc\Diagnostics\Excp;
    
    class Router {
        /** @var string subdomain */
        private $subDomain  = NULL;
        /** @var array parsed full route to array */
        private $route      = array();
        /** @var array Addrees */
        private $address    = array(
            'subdomain' => '', 
            'dir' => array(),
            'class' => ''
            );
        
        
        /**
         * Parse URL and create varialble with all important data
         */
        public final function __construct() {
            /** Get domain, e.g. admin, mobile, etc. */
            list($this->subDomain, $null) = explode('.', DOMAIN);
            
            /** parse domain path to array */
            if(ROUTE) {
                $this->route = explode('/', $_GET['route']);
            } 
            
            /** set subdomain to variable address */
            if($this->subDomain != NULL && $this->subDomain != 'www' && is_dir(ROOT . PRESENTER . $this->subDomain)) {
                $this->address['subdomain'] = $this->subDomain;
            } else {
                $this->address['subdomain'] = 'website';
            }
            
            
            $dir = PRESENTER . $this->address['subdomain'] . '/';
            $i = 0;

            /** Set dir names */
            while(1) {
               if(count($this->route) >= $i + 1 && is_dir(ROOT . $dir . $this->route[$i])) {
                    $this->address['dir']['d' . $i] = $this->route[$i];
                    $dir .= $this->route[$i] . '/';
                } else {
                    break;
                }
                $i++;
            }
            
            
            $cDir = str_replace('/', '\\', $dir);

            /** Set class */
            if(count($this->route) > $i && class_exists($cDir . $this->route[$i])) {
                $this->address['class'] = $this->route[$i];
            } else {
                if(class_exists($cDir . 'Index')) {
                    $this->address['class'] = 'Index';
                } else {
                    new Excp('E_CLASS_NO_EXISTS');
                }
            }
            $i++;

            /** Set method */
            if(count($this->route) > $i && method_exists($cDir . $this->address['class'], $this->route[$i])) {
                $this->address['method'] = $this->route[$i];
                $i++;
            }
            
            if(count($this->route) > $i) {
                $this->address['params'] = array_slice($this->route, $i);
            }
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
            if(!is_array($input)) {
                $input = explode(':', $input);
            }
            
            if($input[0] == 'www' || is_dir(ROOT . PRESENTER . $input[0])) {
                $subDomain = $input[0];
                unset($input[0]);
                $out = PROTOCOL;
                
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
         * Redirect to web by special syntax for traceRoute
         * @param string $input 
         */
        public static function redirect($input, $inURL = FALSE) {
            $search = $GLOBALS['router']->traceRoute(substr($input, strrpos($input, ':')));
            
            if($inURL && preg_match('@' . $search . '@i', $_SERVER['SCRIPT_URI']) || !$inURL) {
                if(Ajax::isAjax()) {
                    exit('{"redirect" : "' . $GLOBALS['router']->traceRoute($input) . '"}');
                } else {
                    header('location: ' . $GLOBALS['router']->traceRoute($input));
                }    
            }
        }
        
             
        public static function getDomain() {
            $domain = array_reverse(explode('.', DOMAIN));
            return $domain[1] . '.' . $domain[0];
                    
        }
        
        
        public final function getLink() {
            return $this->address['subdomain'] . ':' . implode(':', $this->address['dir']) . ':' . $this->address['class'] . (isset($this->address['method']) ? ':' . $this->address['method'] : '');
        }
    }