<?php
    /**
     * String is classs for working with string
     *
     * @category   Yucat
     * @package    Includes
     * @name       string
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc;

    class String {
        
        /**
         * Replace a '?' in $args[0] by array of $args
         * @param array $args
         * @return string
         */
        public static function paramsReplace(array $args) {
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
        
        
        public static function keyGen($len) {
            $hash = '';
            $chars = '1234567890QWERTZUIOPLKJHGFDSAYXCVBNM';
            for($i=0;$i<$len;$i++) {
                $hash .= $chars[rand(0, strlen($chars)-1)];
            }
            return $hash;
        }
    }