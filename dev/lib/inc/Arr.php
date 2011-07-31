<?php
    /**
     * Arr is class for work with array
     *
     * @category   Yucat
     * @package    Includes
     * @name       Arr
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.1
     */

    namespace inc;

    class Arr {
         
        /** Lock dynamic call */
        private function __construct() {}
         
         
         /**
         * Check if in $array is $check
         * @param array $array Array for check
         * @param array $check Checking array
         * @return BOOL
         * @deprecated
         */
        public static function isInArray(array $array, array $check) {
            if(count($array) != count($check)) return FALSE;
            
            foreach($check AS $value) {
                if(!array_key_exists($value, $array)) return FALSE;
            }
            return TRUE;
        }
        
        
        /**
         * Implode array keys by delimiter
         * @param array $array
         * @param string $delimiter
         * @return string
         */
        public static function implodeArrayKeys(array $array, $delimiter) {
            $array = array_keys($array);
            return implode($delimiter, $array);
        }
        
        
        /**
         * Protect array before injection
         * @param array $array
         * @return array 
         */
        public static function treatArrayValue(array $array) {
            foreach($array AS $param => $value) {
                $array[$param] = Security::protect($value, FALSE);
            }
            return $array;
        }
        
        
       
        public static function arrayKeyReplace($what, $input, array $array) {
            $out = array();

            foreach($array as $key => $val) {
                $out[str_replace($what, $input, preg_quote($key, '/'))] = $val;
            }
            return $out;
        }
     }