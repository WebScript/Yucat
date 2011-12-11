<?php
    /**
     * AJAX
     *
     * @category   Yucat
     * @package    Includes
     * @name       Ajax
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc;
    
    class Ajax {
        private static $content;
        /** Sending template content? */
        private static $isJSON = FALSE;


        public static function isAjax() {
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        public static function sendJSON(array $json) {
            self::$content = json_encode($json);
            self::$isJSON = TRUE;
        }
        
        
        public static function sendHTML($html) {
            self::$content .= $html;
        }
                
        
        public static function getContent() {
            return self::$content;
        }
        
        
        public static function getMode() {
            return self::$isJSON;
        }        
    }