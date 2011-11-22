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
     * @version    Release: 0.1.6
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.1
     */

    namespace inc;

    class Arr {
         
        /** Lock dynamic call */
        private function __construct() {}
         
         

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
         * @param array $array
         * @param string $delimiter
         * @return string
         */
        public static function implodeArrayKeys(array $array, $delimiter) {
            $array = array_keys($array);
            return implode($delimiter, $array);
        }
        
       
        public static function arrayKeyReplace($what, $input, array $array) {
            $out = array();

            foreach($array as $key => $val) {
                $out[str_replace($what, $input, preg_quote($key, '/'))] = $val;
            }
            return $out;
        }
        
        
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
        
        public static function array2Object(array $array) {
            $obj = new \StdClass();

            foreach($array as $key => $val){
                $obj->$key = $val;
            }
            
            return $obj;
        }
     }