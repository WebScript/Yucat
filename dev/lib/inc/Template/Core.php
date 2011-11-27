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
     * @version    Release: 0.3.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc\Template;
    
    use inc\Ajax;
    use inc\Cache;
    use inc\Diagnostics\ErrorHandler;
    
    class Core {
            
        public static $translate = array();
        
        public final function __construct() {
            GLOBAL $router;
            
            /** load main page */
            $template = STYLE_DIR . STYLE . $router->getParam('subDomain') . '/layer.html';
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
            $templete = $parse->parseTemplate($template, $parse->getMacros());
            
            
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