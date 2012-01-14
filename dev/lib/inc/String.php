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
     * @version    Release: 0.1.2
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;

    class String {
        
        /** This is static class */
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
        
        
        /**
         * Write and formate source code for Developer version of ErrorHandler
         * 
         * @param string $file link to file 
         * @param integer number of line
         */
        public static function highlightSource($file, $line) {
            if($line < 7) return FALSE;
            if(function_exists('ini_set')) {
                ini_set('highlight.html', '#06B');
                ini_set('highlight.default', '#000');
                ini_set('highlight.comment', '#998; font-style: italic');
                ini_set('highlight.string', '#080');
                ini_set('highlight.keyword', '#0067CF; font-weight: bold');
                }
                
            $source = highlight_string(file_get_contents($file), TRUE);
            $source = str_replace("\n", '', $source);
            $source = explode('<br />', $source);
            $source = array_slice($source, ($line - 7), 14);
            
            $i = $line - 6;
            $output = '';
            foreach($source as $value) {
               
                if($i == $line) {
                    $output .= '<span class="highlight">Line ' . $i
                             . ': ' . strip_tags($value) . "</span>";
                } else {
                    $output .= '<span class="line">Line ' . $i 
                             . ':</span>' . $value . "\n";
                }
                
                $i++;
            }
            return $output;
        }
    }