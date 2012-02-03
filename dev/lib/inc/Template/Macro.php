<?php
    /**
     * Macros for template system
     *
     * @category   Yucat
     * @package    Library\Template
     * @name       Macro
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.4.3
     * @link       http://www.yucat.net/documentation
     */

    namespace inc\Template;
    
    use inc\Ajax;
    use inc\Router;    
    use inc\Diagnostics\Excp;
    
    class Macro {
        /** @var array Array of macros */
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
         * 
         * @param string $macro
         * @param string $function 
         */
        public final function addMacro($macro, $function) {
            if(!array_key_exists($macro, $this->macros)) {
                $this->macros[$macro] = $function;
            }
        }
        
        
        /**
         * Get all macros
         * 
         * @return array macros
         */
        public final function getMacros() {
            return $this->macros;
        }
        
        
        /**
         * Include command in Viewer's template
         * 
         * @param string $name
         * @param string $method
         * @return string
         */
        public final function macroInclude($name, $method = NULL) {
            list($subdomain, $other) = explode('/', $name);

            if(substr_count($name, '/') >= 2) {
                $other = substr($name, strpos($name, '/') + 1);
                $other = str_replace('/', '_', $other);
            }

            $styleDir = STYLE_DIR . STYLE . '/' . $subdomain
                   . '/template/' . $other 
                   . ($method ? '_' . ucfirst($method) : '') 
                   . '.html';

            $parse = new Parse();
            $presenter = str_replace('/', '\\', PRESENTER . $name);
            
            Core::$translate = array_merge(Core::$translate, $GLOBALS['lang']->getTranslate($subdomain . '/' . $other));
            Core::$presenter = array_merge(Core::$presenter, array($presenter));

            if($method) {
                Core::$method[array_search($presenter, Core::$presenter)] = $method;
            }

            if(file_exists($styleDir) && filesize($styleDir) > 0) {
                $f = fopen($styleDir, 'r');
                $template = fread($f, filesize($styleDir));
                fclose($f);
            } else new Excp('E_ISE', 'E_TEMPLATE_NO_EXISTS');

            $template = isset($template) ? $parse->parseTemplate($template, $this->getMacros()) : '';
            return $template;
        }
        
        
        /**
         * Content command in Viewer's template
         * 
         * @return string 
         */
        public final function macroContent() {
            $router = Router::_init();
            $link = $router->getParam('subdomain') . ($router->getParam('dir') ? '/' . implode('/', $router->getParam('dir')) : '') . '/' . $router->getParam('class');
            return $this->macroInclude($link, $router->getParam('method'));
        }
        
        
        /**
         * Link command in Viewer's template
         * 
         * @param string $val
         * @return string 
         */
        public final function macroLink($val) {
            return Router::traceRoute($val);
        }
    }