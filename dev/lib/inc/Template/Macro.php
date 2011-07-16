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
     * @version    Release: 0.2.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.2.3
     * 
     * @todo Dorobit dokumentaciu
     */

    namespace inc\Template;
    
    class Macro {
        private $macros = array();
        
        public function __construct() {
            $this->addMacro('include %key', 'macroInclude(%key)');
            $this->addMacro('if %key :', 'if(%key):');
            $this->addMacro('/if', 'endif;');
            $this->addMacro('foreach %key :', 'foreach(%key):');
            $this->addMacro('/foreach', 'endforeach;');
        }
        
        
        public function addMacro($macro, $function) {
            if(!array_key_exists($macro, $this->macros)) {
                $this->macros[$macro] = $function;
            }
        }
        
        
        public function getMacros() {
            return $this->macros;
        }
    }