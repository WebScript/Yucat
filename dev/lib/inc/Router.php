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
     * @version    Release: 0.2.9
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc;
    
    use inc\Diagnostics\ErrorHandler;
    
    class Router {
        private $subDomain  = NULL;
        private $address    = array(
            'subdomain' => '', 
            'dir' => array(),
            'class' => ''
            );
        private $route      = array();
        
        
        
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
                    ErrorHandler::error404();
                }
            }
            $i++;

            /** Set method */
            if(count($this->route) > $i && method_exists($cDir . $this->address['class'], $this->route[$i])) {
                $this->address['method'] = $this->route[$i];
            }
        }
       
       
        
        public final function getAddress() {
            return $this->address;
        }
        
        
        
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
         * @param string $input
         * @return string
         */
        public final function traceRoute($input) {
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
        public final function redirect($input, $inURL = FALSE) {
            $search = self::traceRoute($input);
            
            if(!preg_match('@' . $search . '@', $_SERVER['SCRIPT_URI']) && $inURL || !$inURL) {
                if(Ajax::isAjax()) {
                    exit('{"redirect" : "' . $this->traceRoute($input) . '"}');
                } else {
                    header('location: ' . $this->traceRoute($input));
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
            $search = $GLOBALS['router']->traceRoute($search);
            
            if(!preg_match('@' . $search . '/(.*)@', $_SERVER['SCRIPT_URI'])) {
                $GLOBALS['router']->redirect($input);
            }
        }
    }