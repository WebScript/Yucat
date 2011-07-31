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
     * @version    Release: 0.2.8
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
            $this->addMacro('macroInclude %key', '');
            $this->addMacro('macroContent', '');
            $this->addMacro('if %key :', 'if(\\1):');
            $this->addMacro('ifset %key :', 'if(isset(\\1)):');
            $this->addMacro('/if', 'endif;');
            $this->addMacro('/ifset', 'endif;');
            $this->addMacro('elseif', 'elseif(\\1):');
            $this->addMacro('elseifset', 'elseif(isset(\\1)) :');
            $this->addMacro('else', 'else :');
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
            if(file_exists($template)) {
                $f = fopen($template, 'r');
                $template = fread($f, filesize($template));
                fclose($f);
            } else \inc\Diagnostics\ErrorHandler::error404();
            
            $parse = new Parse();
            $name = ucwords(str_replace('_', ' ', $name));
            $presenter = '\\Presenter\\' . str_replace(' ', '\\', $name);
            if(class_exists($presenter)) {
                $presenter = new $presenter;

                Core::$translate = array_merge(get_object_vars($presenter->getTemplate()), Core::$translate);
                $template = $parse->parseTemplate($template, $this->getMacros());
                return $template;
            } else \inc\Diagnostics\ErrorHandler::error404();
        }
        
        public function macroContent() {
            $router = new \inc\Router();
            $address = $router->getAddress();
            $addr = strtolower($address[0]) . '_' . strtolower($address[1]);
            return $this->macroInclude($addr);
        }
    }