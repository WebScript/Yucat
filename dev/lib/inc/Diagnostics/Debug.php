<?php
    /**
     * Debug class with extends ErrorHandler is main class for debugging 
     * and fixing scripts, classes, models, etc.
     * This class have two mods: Developer and Production.
     * In Developer mode you can see errors in +/- 5 lines of code about error
     * and have GUI for easier fixing and debugging scripts.
     * In Production mode anyone can't see errors, all errors is logging to DB
     * and if in script is error you seen public_error.html file with error
     * message (Server error 500)
     *
     * @category   Yucat
     * @package    Includes\Diagnostics
     * @name       Debug
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.2.2
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.2.1
     */

    namespace inc\Diagnostics;

    class Debug {
        /** @var Start time for timer() */
        private static $startTime;
        /** @var Mode of debug Production/Development */
        private static $mode = self::MODE_PROD;
        
        /** Development mode*/
        const MODE_DEV = 1;
        /** Production mode */
        const MODE_PROD = 2;
        
        /** You can't call dynamicly this class */
        private function __construct() {}
        
        

        /**
         * Get time in micro seconds
         * @return float
         */
        private static function getTime() {
            List ($usec, $sec) = explode(' ', microtime());
            return ((float)$sec + (float)$usec);
        }
        
        
        /**
         * Calculates the time at which the generated page
         * @param string $input 
         */
        public static function timer($input = FALSE) {
            if(!$input) self::$startTime = self::getTime();
            else {
                d(self::getTime() - self::$startTime);
            }
        }
        
        
        /**
         * Dump input to formated text
         * @param mixed $input
         * 
         * @todo dokoncit
         */
        public static function dump($input) {
            $out = array();
            $out[] = '<div style="background-color: #F0F0F0; float: left;">&nbsp;';
                
            if(is_array($input)) {
                $out[] = self::getArray($input);
            } elseif(is_string($input)) {
                $out[] = 'STRING ';
                $out[] = '"' . $input . '"' . ' (' . strlen($input) . ')';
            } elseif(is_float ($input)) {
                $out[] = 'FLOAT ';
                $out[] = $input . ' (' . strlen($input) . ')';
            } elseif(is_integer($input)) {
                $out[] = 'INTEGER ';
                $out[] = $input . ' (' . strlen($input) . ')';
            } elseif(is_bool($input)) {
                $out[] = 'BOOL ';
                $out[] = $input ? 'TRUE' : 'FALSE';
            } elseif($input === NULL) {
                $out[] = 'NULL';
            } elseif(is_resource($input)) {
                $out[] = 'RESOURCE ';
                $out[] = get_resource_type($input);
            } elseif(is_object($input)) {
                $out[] = 'OBJECT ';
                $out[] = self::getArray(get_object_vars($input));
                $out[] = 'Unknown type!';
            }
            
            $out[] = '&nbsp;</div><br /><br /><br />';
            echo implode('', $out);
        }
        
        
        
        public static function getArray(array $array, $space = '') {
            $out = array();
            $out[] = 'Array (<br />';
            $space1 = $space . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            
            foreach($array as $key => $val) {
               $val = !$val ? 'NULL' : $val;
               $out[] = $space1;
               
               if(is_array($val)) {
                   $out[] = (is_numeric($key) ? $key : '"'.$key.'"')
                          . ' => ' . self::getArray($val, $space1);
               } elseif(is_object($val)) {
                   $out[] = (is_numeric($key) ? $key : '"'.$key.'"')
                          . ' => ' . self::getArray(get_object_vars($val), $space1);
               } else {
                   $out[] = (is_numeric($key) ? $key : '"' . htmlspecialchars($key) . '"')
                          . ' => '
                          . (is_numeric($val) || $val === 'NULL' ? $val : '"' . htmlspecialchars($val) . '"')
                          . ' (' . strlen($val) . ')<br />';
               }
            }
            
            $out[] = $space . ');<br />';
            return implode('', $out);
        }
        
        
        /**
         * Set woriking mode Devepoler/Production
         * @param string $mode 
         */
        public static function setMode($mode) {
            if($mode === self::MODE_DEV || $mode === self::MODE_PROD) {
                self::$mode = $mode;
            }
        }
        
        public static function getMode() {
            return self::$mode;
        }
    }