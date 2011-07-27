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
     * @version    Release: 0.2.2
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc\Template;
    
    class Core {
            
        
        public function __construct() {
            $router = new \inc\Router();
            $address = $router->getAddress();
            
            $template = STYLE_DIR . STYLE . '/layer.html';
            $f = fopen($template, 'r');
            $template = fread($f, filesize($template));
            fclose($f);
            
            $basePresenter = new \Presenter\BasePresenter();
            $parse = new Parse();

            //Set vars $template->any as $any
            foreach(get_object_vars($basePresenter->template) as $key => $val) {
                $$key = $val;
            }
            
            $template = $parse->parseTemplate($template, $parse->getMacros());
            
            
        }
        
        
        /**
         * Main function for work with MVP
         */
        public function templateTranslate() {   
            
            $parse = new Parse();
            //$template = $parse->translate($template, $basePresenter->getVar(), '$');
            //$template = $parse->parseSpecial($template, $parse->getMacros());
            
            $name = rand(11111, 99999);
            $cache = new \inc\Cache('cache');
            $cache->createCache($name, $template);
            $cache->includeCache($name);
            $cache->deleteCache($name);
        }
        
        
        
        /* Testing */
        public static function presenterInclude(array $address) {
            $template = STYLE_DIR . STYLE . '/template/' 
                      . strtolower($address[1]) . '_' 
                      . strtolower($address[2]) . '.html';
            
            $presenter = $router->callPresenter();
            
            $f = fopen($template, 'r');
            $template = fread($f, filesize($template));
            fclose($f);
            
            
            /*
             * Tu sa zavola parser pre macra a nastavia sa premene z $this->template->neco na $neco
             */
            $parse = new Parse();
            $template = $parse->translate($template, $presenter->getVar(), '$');
            $template = $parse->parseSpecial($template, $parse->getMacros());
            return $template;
        }
    }