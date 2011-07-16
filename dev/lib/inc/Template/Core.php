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
     * @version    Release: 0.1.7
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     * 
     * @todo dorobuit dokumentaciu
     */

    namespace inc\Template;
    
    class Core {
            
        
        public function __construct() {
            
        }
        
        public function templateTranslate() {
            $router = new \inc\Router();
            $address = $router->getAddress();
            
            $template = array_slice($address, 0, 2);
            $template = implode('/', $template);
            $template = STYLE_DIR . STYLE . '/template/' . $template . '_' . $address[2] . '.html';
            
            $router->callPresenter();
            
            $f = fopen($template, 'r');
            $template = fread($f, filesize($template));
            
            //Sem pridat este template translate
            
            $name = rand(11111, 99999);
            $cache = new \inc\Cache('cache');
            $cache->createCache($name, $template);
            
            include TEMP . 'cache/' . $name;
            
            //Sem pridat este delete template
        }
    }