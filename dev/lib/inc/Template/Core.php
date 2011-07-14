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
     * @version    Release: 0.0.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.0
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
                '{$%key}' => '{echo $%key}'
            );
        }

        
        public function addMacro($macro, $function) {
            if(!array_key_exists($macro, $this->macros)) {
                $this->macros[$macro] = $function;
            }
        }
              
        public function translate($website) {
            
        }
        
    }