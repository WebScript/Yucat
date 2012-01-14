<?php
    /**
     * Arr is class for work with array
     *
     * @category   Yucat
     * @package    Library
     * @name       Arr
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.2.0
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;

    class Arr {
         
        /** This is static class */
        private function __construct() {}
         
         
        /**
         * @param array $needle
         * @param array $haystack
         * @return BOOL 
         */
        public static function isInArray(array $needle, array $haystack) {
            $needle = array_keys($needle);
            $haystack = array_keys($haystack);
            
            if(count($needle) > count($haystack)) return FALSE;
            foreach($needle AS $val) {
                if(!array_search($val, $haystack)) return FALSE;
            }
            return TRUE;
        }
        
        
        /**
         * Implode array keys by delimiter
         * 
         * @param array $array Array
         * @param string $delimiter Delimiter
         * @return string
         */
        public static function implodeArrayKeys(array $array, $delimiter) {
            $array = array_keys($array);
            return implode($delimiter, $array);
        }
        
       
        /**
         * Replace array keys
         * 
         * @param string $what What
         * @param strign $input With
         * @param array $array
         * @return array Replaced
         */
        public static function arrayKeyReplace($what, $input, array $array) {
            $out = array();

            foreach($array as $key => $val) {
                $out[str_replace($what, $input, preg_quote($key, '/'))] = $val;
            }
            return $out;
        }
        
        
        /**
         * Determine whether is in extended array
         * @param array $array
         * @param type $search
         * @return type 
         */
        public static function isInExtendedArray(array $array, $search) {
            foreach($array as $key => $val) {
                if(is_array($val)) {
                   if(self::isInExtendedArray($val, $search)) {
                       return TRUE;
                   }
                } elseif($val == $search) {
                    return TRUE;
                }
            }
            return FALSE;
        }
        
        
        /**
         * Convert array to object
         * 
         * @param array $array Array to convert
         * @return object Converted object
         */
        public static function array2Object(array $array) {
            $obj = new \StdClass();

            foreach($array as $key => $val){
                $obj->$key = $val;
            }
            
            return $obj;
        }
     }