<?php
    /**
     * Class for work with AJAX
     *
     * @category   Yucat
     * @package    Library
     * @name       Ajax
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.2.0
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;
    
    class Ajax {
        /** @var string Content of AJAX response */
        private static $content;
        /** @var BOOL Sending template content or JSON? */
        private static $isHTML = TRUE;

        /** This is static class */
        private function __construct() {}
        
        
        /**
         * Get whether is AJAX request
         * 
         * @return BOOL
         */
        public static function isAjax() {
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        /**
         * Send JSON
         * 
         * @param array $json Array of JSON
         */
        public static function sendJSON(array $json) {
            self::$content = self::drawJSON($json);
            self::$isHTML = FALSE;
        }
        
        
        /**
         * Send HTML
         * 
         * @param string $html HTML
         */
        public static function sendHTML($html) {
            self::$content .= $html;
        }
                
        
        /**
         * Get content from $content
         * 
         * @return string content
         */
        public static function getContent() {
            return self::$content;
        }
        
        
        /**
         * Get whether is HTML or JSON response
         * 
         * @return BOOL
         */
        public static function isHTML() {
            return self::$isHTML;
        }     
        
        
        /**
         * Create JSON from array
         * 
         * @param array $array Array of JSON's params and values
         * @return string
         */
        private static function drawJSON(array $array) {
            $out = array();
            
            foreach($array as $key => $val) {
                if(is_array($val)) {
                    $out[] = '"' . $key . '" : ' . self::drawJSON($val);
                } else {
                    $out[] = '"' . $key . '" : "' . htmlspecialchars($val) . '"';
                }
            }
            
            return '{' . implode(', ', $out) . '}';
        }
    }