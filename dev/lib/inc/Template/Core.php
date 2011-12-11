<?php
    /**
     * Core of work woth templates
     *
     * @category   Yucat
     * @package    Includes\Template
     * @name       Core
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.3.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc\Template;
    
    use inc\Ajax;
    use inc\Cache;
    use inc\Diagnostics\ErrorHandler;
    
    class Core {
            
        public static $translate = array();
        
        public static $presenter = array();
        public static $method    = array();
        
        
        
        public final function __construct() {
            GLOBAL $router;
            
            /** load main page */
            $template = STYLE_DIR . STYLE . '/' . $router->getParam('subdomain') . '/layer.html';
            if(file_exists($template)) {
                $f = fopen($template, 'r');
                $template = fread($f, filesize($template));
                fclose($f);
            } else {
                ErrorHandler::error404();
            }
            
            
            if(Ajax::isAjax()) {
                echo Ajax::getContent();
            }
            
            $parse = new Parse();
            $template = $parse->parseTemplate($template, $parse->getMacros());
            
            if(empty(self::$presenter)) {
                self::$presenter = array('Presenter\\website\\Index');
            }
            
            foreach(self::$presenter as $key => $val) {
                if(class_exists($val)) {
                    $presenter = new $val;
                    
                    if(isset(self::$method[$key])) {
                        if(method_exists($presenter, self::$method[$key])) {
                            call_user_func(array($presenter, self::$method[$key]));
                        } else ErrorHandler::error404();
                    }
                    self::$translate = array_merge(self::$translate, get_object_vars($presenter->getTemplate()));
                } else ErrorHandler::error404();
            }
            
           
            
            if(Ajax::isAjax() && Ajax::getMode() || !Ajax::isAjax()) {
                foreach(self::$translate as $key => $val) {
                    $$key = $val;
                }
                $template = $parse->setVariable($template);

                $name = rand(11111, 99999) . '.phtml';
                $cache = new Cache('cache');
                $cache->createCache($name, $template);
                include TEMP . 'cache/' . $name;
                $cache->deleteCache($name);
            }
        }
    }