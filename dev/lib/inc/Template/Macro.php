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
     * @version    Release: 0.3.8
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.2.3
     */

    namespace inc\Template;
    
    use inc\Router;
    use inc\Diagnostics\ErrorHandler;
    
    class Macro {
        /** @var array of macros */
        private $macros = array();
        
        
        /**
         * Call default macrss
         */
        public function __construct() {
            $this->addMacro('macroInclude %key', '');
            $this->addMacro('macroContent', '');
            $this->addMacro('macroLink %key', '');
            $this->addMacro('if %key :', 'if(\\1):');
            $this->addMacro('ifset %key :', 'if(isset(\\1)):');
            $this->addMacro('/if', 'endif;');
            $this->addMacro('/ifset', 'endif;');
            $this->addMacro('elseif %key :', 'elseif(\\1):');
            $this->addMacro('elseifset %key :', 'elseif(isset(\\1)) :');
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
        
        
        
        public function macroInclude($name, $method = NULL, $params = NULL) {
            Core::$translate = array_merge(Core::$translate, $GLOBALS['lang']->getTranslate($name));
            
            $templ_dir = STYLE_DIR . STYLE 
                   . '/template/' . $name 
                   . ($method ? '_' . $method : '') 
                   . '.html';
            
            $parse = new Parse();
            $name2 =  explode('_', $name);
            
            if(isset($name2[1])) {
                $presenter = '\\Presenter\\' . ucwords($name2[0]) . '\\' . ucwords($name2[1]);
            } else {
                $presenter = '\\Presenter\\' . ucwords($name2[0]);
            }

            if(class_exists($presenter)) {
                $presenter = new $presenter;             
                
                if(method_exists($presenter, $method)) {
                    call_user_func_array(array($presenter, $method), $params);
                } elseif($method !== NULL) ErrorHandler::error404();
                Core::$translate = get_object_vars($presenter->getTemplate());
                
                if(file_exists($templ_dir)) {
                    $f = fopen($templ_dir, 'r');
                    $template = fread($f, filesize($templ_dir));
                    fclose($f);
                } elseif($method === NULL) ErrorHandler::error404();

                $template = isset($template) ? $parse->parseTemplate($template, $this->getMacros()) : '';
                return $template;
            } else ErrorHandler::error404();
        }
        
        
        
        public function macroContent() {
            $addr = Router::getAddress();
            $link = Router::getLevel() >= 2 ? ($addr['dir'] ? strtolower($addr['dir']) . '_' : '') . strtolower($addr['class']) : strtolower($addr['class']);
            d($ling);
            return $this->macroInclude($link, $addr['method'], Router::getOnlyParam());
        }
        
        
        
        public function macroLink($val) {
            return Router::traceRoute($val);
        }
    }