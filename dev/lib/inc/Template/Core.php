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
     * @version    Release: 0.1.0
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
            
            \inc\Diagnostics\Debug::dump($address);
            
            $template = array_slice($address, 0, 2);
            $template = implode('/', $template);
            $template = STYLE_DIR . STYLE . '/' . $template . '.html';
            
            echo $template;
            
            $router->callPresenter();
            
            
            //fopen(, $mode)
            
        }
    }