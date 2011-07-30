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
            $this->addMacro('macroInclude %key', 'test');
            $this->addMacro('if %key :', 'if(\\1):');
            $this->addMacro('/if', 'endif;');
            $this->addMacro('foreach %key :', 'foreach(\\1):');
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
        
        
        public function macroInclude($name) {
            $template = STYLE_DIR . STYLE . '/template/' . $name . '.html';
            $f = fopen($template, 'r');
            $template = fread($f, filesize($template));
            fclose($f);
            
            $parse = new Parse();
            $name = ucwords(str_replace('_', ' ', $name));
            $presenter = '\\Presenter\\' . str_replace(' ', '\\', $name);
            $presenter = new $presenter;
            Core::$translate = get_object_vars($presenter->getTemplate());
            $template = $parse->parseTemplate($template, $this->getMacros());
            return $template;
        }
    }