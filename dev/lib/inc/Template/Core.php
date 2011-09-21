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
    
    class Core {
            
        public static $translate = array();
        
        public function __construct() {
            $template = ROOT . STYLE_DIR . STYLE . '/layer.html';
            $f = fopen($template, 'r');
            $template = fread($f, filesize($template));
            fclose($f);

            $parse = new Parse();
            $template = $parse->parseTemplate($template, $parse->getMacros());
            
            if(\inc\Ajax::isAjax()) {
                echo \inc\Ajax::getContent();            
            }
            
            if(\inc\Ajax::isAjax() && \inc\Ajax::getMode() || !\inc\Ajax::isAjax()) {
                foreach(self::$translate as $key => $val) {
                    $$key = $val;
                }
                $template = $parse->setVariable($template);

                $name = rand(11111, 99999) . '.phtml';
                $cache = new \inc\Cache('cache');
                $cache->createCache($name, $template);
                include ROOT . '/temp/cache/' . $name;
                //$cache->deleteCache($name);
            }
        }
    }