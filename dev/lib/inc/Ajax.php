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
     * @version    Release: 0.2.1
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;
    
    final class Ajax {
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
            self::$content = json_encode($json);
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
    }