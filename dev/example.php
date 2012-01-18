<?php
    /**
     * Some info about this class
     *
     * @category   (Yucat | Your website)
     * @package    (Library | Model | Presenter | Other)
     * @name       class name
     * @author     Author e.g. Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: (0.0.0 | 0.0.0a | 0.0.00)
     * @link       http://www.yucat.net/documentation
     * 
     * @todo any todo
     */

     /** Namespaces */
    namespace inc;
    
    /** Uses */
    use inc\Diagnostics\ExceptionHandler;

    /** main class */
    class db {
        /** All class variables */
        protected $xyz;
        private $abc;
        public $aaa;
        
        /** contruct */
        public function _construct($error, $type = NULL, $id = NULL) {
            
        }
        // OR for static class
        private function __construst() {}
        
        
        
        
        /** 
         * final functions is uses in Library 
         * is not property don't use static functions
         * 
         */
        /** methods */
        public final function abc() {
            
        }
        
        /**
         * some informatioun about this method
         * 
         * @param array $a Info...
         * @param type $b Info...
         * @param type $c Info...
         * @param Config $d Info...
         * @return integer Info...
         */
        public function tst(array $a, $b, $c, Config $d) {
            return 1;
        }
        
        
        /** destruct */
        public function __destruct() {
            
        }
        
        
 
        
    }
    
    /** Without ?> */