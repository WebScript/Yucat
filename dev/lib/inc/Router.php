<?php
    /**
     * This is main class for work with page system and template system.
     * This class getting and parsing URL and call the relevant method and class
     *
     * @category   Yucat
     * @package    Includes
     * @name       Router
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.3.4
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
                    ErrorHandler::error404('Router -> Class doesn\'t exit');
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
        public static function traceRoute($input) {
            if(!is_array($input)) {
                $input = explode(':', $input);
            }
            
            if($input[0] == 'www' || is_dir(ROOT . PRESENTER . $input[0])) {
                $subDomain = $input[0];
                unset($input[0]);
                
                if(strpos(DOMAIN, $subDomain) !== FALSE) {
                    $subDomain = '';
                }
                
                return $GLOBALS['conf']['protocol']
                . $subDomain
                . DOMAIN 
                . '/'
                . implode('/', $input);
            } else {
                return $GLOBALS['conf']['protocol']
                . DOMAIN
                . '/'
                . implode('/', $input);
            }
        }

        
        /**
         * Redirect to web by special syntax for traceRoute
         * @param string $input 
         */
        public static function redirect($input, $inURL = FALSE) {
            $search = $GLOBALS['router']->traceRoute($input);
            
            if(!preg_match('@' . $search . '@', $_SERVER['SCRIPT_URI']) && $inURL || !$inURL) {
                if(Ajax::isAjax()) {
                    exit('{"redirect" : "' . $GLOBALS['router']->traceRoute($input) . '"}');
                } else {
                    header('location: ' . $GLOBALS['router']->traceRoute($input));
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

            if(!$GLOBALS['router']->getParam('method')) {
                $GLOBALS['router']->redirect($input);
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