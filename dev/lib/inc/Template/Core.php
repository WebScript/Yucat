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
        private $macros = array();       
        
        public function __construct() {
            //test
            $this->macros = array(
                'include %key' => 'macroInclude(%key)',
                'if(%key):' => 'if(%key):',
                'endif;' => 'endif;',
            );
        }

        
        public function addMacro($macro, $function) {
            if(!array_key_exists($macro, $this->macros)) {
                $this->macros[$macro] = $function;
            }
        }
           
        
        
        public function varTranslate($text, $replace, $var = FALSE) {
            if(is_object($replace)) {
                $replace = get_object_vars($replace);
            }
            
            foreach($replace as $key => $val) {
                $text = str_replace('{' . ($var ? '$' : '') . $key . '}', $replace, $text);
            }
            
            return implode('', $out);
        }
        
    }