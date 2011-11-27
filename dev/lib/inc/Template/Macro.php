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
        public final function addMacro($macro, $function) {
            if(!array_key_exists($macro, $this->macros)) {
                $this->macros[$macro] = $function;
            }
        }
        
        
        /**
         * Get macros
         * @return array macros
         */
        public final function getMacros() {
            return $this->macros;
        }
        
        
        
        public final function macroInclude($name, $method = NULL) {
            Core::$translate = array_merge(Core::$translate, $GLOBALS['lang']->getTranslate($name));
            
            $styleDir = STYLE_DIR . STYLE 
                   . '/template/' . $name 
                   . ($method ? '_' . $method : '') 
                   . '.html';
            
            $parse = new Parse();

            $presenter = PRESENTER . str_replace('/', '\\', $name);
            if(class_exists($presenter)) {
                $presenter = new $presenter;             
                
                if(method_exists($presenter, $method)) {
                    call_user_func(array($presenter, $method));
                } elseif($method !== NULL) ErrorHandler::error404();
                
                Core::$translate = array_merge(Core::$translate, get_object_vars($presenter->getTemplate()));
                
                if(file_exists($styleDir)) {
                    $f = fopen($styleDir, 'r');
                    $template = fread($f, filesize($styleDir));
                    fclose($f);
                } else ErrorHandler::error404();

                $template = isset($template) ? $parse->parseTemplate($template, $this->getMacros()) : '';
                return $template;
            } else ErrorHandler::error404();
        }
        
        
        
        public final function macroContent() {
            GLOBAL $router;
            $link = implode('/', $router->getParam('dir')) . $router->getParam('class');
            
            //d($link, TRUE);
            return $this->macroInclude($link, $router->getParam('method'));
        }
        
        
        
        public final function macroLink($val) {
            GLOBAL $router;
            return $router->traceRoute($val);
        }
    }