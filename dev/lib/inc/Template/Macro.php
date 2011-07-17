<?php
    /**
     * Macros for template system
     *
     * @category   Yucat
     * @package    Includes\Template
     * @name       Macro
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.2.4
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.2.3
     * 
     * @todo Dorobit dokumentaciu
     */

    namespace inc\Template;
    
    class Macro {
        /** @var array of macros */
        private $macros = array();
        
        
        /**
         * Call default macrss
         */
        public function __construct() {
            $this->addMacro('include', 'macroInclude()');
            $this->addMacro('if %key :', 'if(%key):');
            $this->addMacro('/if', 'endif;');
            $this->addMacro('foreach %key :', 'foreach(%key):');
            $this->addMacro('/foreach', 'endforeach;');
        }
        
        
        /**
         * Add macro for template system
         * @param string $macro
         * @param string $function 
         */
        public function addMacro($macro, $function) {
            if(!array_key_exists($macro, $this->macros)) {
                $this->macros[$macro] = $function;
            }
        }
        
        
        /**
         * Get macros
         * @return array macros
         */
        public function getMacros() {
            return $this->macros;
        }
        
        
        
        public static function macroInclude() {
            $router = new \inc\Router();
            $address = $router->getAddress();
            
            $template = array_slice($address, 0, 2);
            $template = implode('/', $template);
            $template = STYLE_DIR . STYLE . '/template/' . $template . '_' . strtolower($address[2]) . '.html';
            
            $presenter = $router->callPresenter();
            
            $f = fopen($template, 'r');
            $template = fread($f, filesize($template));
            fclose($f);
            
            
            //Este pridat language translator
            $parse = new Parse();
            $template = $parse->translate($template, $presenter->getVar(), '$');
            $template = $parse->parseSpecial($template, $parse->getMacros());
             return $template;
        }
    }