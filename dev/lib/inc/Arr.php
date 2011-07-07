<?php
    namespace inc;

    /**
     * Arr is class for work with array
     *
     * @category   Yucat
     * @package    Includes
     * @name       Arr
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Yucat Technologies (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.1
     */

    class Arr {
         
         private function __construct() {}
         
         
         /**
         * Check if in $array is $check
         * @param array $array Array for check
         * @param array $check Checking array
         * @return BOOL
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
        
        
        /**
         * Replace value 2nd array with value 1st array by param 1st == value 2nd
         * e.g.
         * array('one' => 'apple', 'five' => 'waterMelon')
         * array('one', 'two', 'three')
         * 
         * @param array $array
         * @param array $input
         * @return array 
         */
        public static function arrayKeyReplace(array $array, array $input) {
            $out = array();

            foreach($input as $value) {
                    $out[] = str_replace(array_keys($array), $array, $value);
            }
            return $out;
        }
     }