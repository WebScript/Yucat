<?php
    /**
     * Core of work woth templates
     *
     * @category   Yucat
     * @package    Library\Template
     * @name       Core
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.4.0
     * @link       http://www.yucat.net/documentation
     */

    namespace inc\Template;
    
    use inc\Ajax;
    use inc\Cache;
    use inc\Router;
    use inc\Diagnostics\Excp;
    
    final class Core {
        /** @var array All translate packs */
        public static $translate = array();
        /** @var array All called presenters */
        public static $presenter = array();
        /** @var array All called methods */
        public static $method    = array();
        
        
        /**
         * This is primary method for load and manage Viewer in MVP scheme
         */
        public function __construct() {
            $router = Router::_init();
            
            /** load main page */
            $template = ROOT . 'styles/' . STYLE . '/' . $router->getParam('subdomain') . '/layer.html';
            if(file_exists($template)) {
                $f = fopen($template, 'r');
                $template = fread($f, filesize($template));
                fclose($f);
            } else {
                new Excp('E_ISE', 'E_LAYER_NO_EXISTS');
            }
            
            $parse = new Parse();
            $template = $parse->parseTemplate($template, $parse->getMacros());
            
            if(empty(self::$presenter)) {
                self::$presenter = array('Presenter\\' .  $router->getParam('subdomain') . '\\Index');
            }
            
            foreach(self::$presenter as $key => $val) {
                if(class_exists($val)) {
                    $presenter = new $val;

                    if(isset(self::$method[$key])) {
                        if(method_exists($presenter, self::$method[$key])) {
                            call_user_func_array(array($presenter, self::$method[$key]), ($router->getParam('params') ? $router->getParam('params') : array()));
                        } else new Excp('E_ISE', 'E_METHOD_NO_EXISTS');
                    }
                    self::$translate = get_object_vars($presenter->getTemplate());
                } else new Excp('E_ISE', 'E_PRESENTER_NO_EXISTS');
            }
            
            if(Ajax::isAjax() && Ajax::isHTML() || !Ajax::isAjax()) {
                foreach(self::$translate as $key => $val) {
                    $$key = $val;
                }
                $template = $parse->setVariable($template);

                $name = rand(10000, 99999) . '.phtml';
                $cache = new Cache('cache');
                $cache->createCache($name, $template); 
                if(Ajax::isAjax()) {
                    ob_start();
                    if(Ajax::isAjax()) echo Ajax::getContent();
                    include ROOT . 'temp/cache/' . $name;
                    $ob = ob_get_contents();
                    ob_end_clean();
                    
                    echo json_encode(array('setContent' => $ob));
                } else {
                    include ROOT . 'temp/cache/' . $name;
                }
                
                $cache->deleteCache($name);
            } else {
                echo Ajax::getContent();
            }
        } 
    }