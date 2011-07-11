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
        
        public function install() {
            
        }
        
        public function addMacro($macro, $start, $end = FALSE) {
            //skontrolovat ci este neexistuje podla keyu
            //pridat key = macro
            // value = start alebo value = array(start, end)
            //end je pre micromacro
        }
        
    }