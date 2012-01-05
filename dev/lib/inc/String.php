<?php
    /**
     * String is class for working with string
     *
     * @category   Yucat
     * @package    Library
     * @name       String
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.1
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;

    class String {
        private function __construct() {}
        
        
        /**
         * Replace a '?' in $args[0] by array of $args
         * 
         * @param array $args input array
         * @return string replaced string
         */
        public static final function paramsReplace(array $args) {
            $string = $args[0];
            unset($args[0]);
            
            if(count($args) != substr_count($string, '?')) return $args[0];
                       
            foreach($args as $val) {
                $first = substr($string, 0, strpos($string, '?'));
                $last = substr($string, strpos($string, '?') + 1);
                $string = $first . Security::protect($val, TRUE) . $last;
            }
            return $string;
        }
        
        
        /**
         * Generate random key
         * 
         * @param integer $len Length of out key
         * @return string key
         */
        public static final function keyGen($len) {
            $hash = '';
            $chars = '1234567890QWERTZUIOPLKJHGFDSAYXCVBNM';
            for($i=0;$i<$len;$i++) {
                $hash .= $chars[rand(0, strlen($chars)-1)];
            }
            return $hash;
        }
    }