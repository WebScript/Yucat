<?php
    /**
     * AJAX
     *
     * @category   Yucat
     * @package    Includes
     * @name       Ajax
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.5
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc;
    
    class Ajax {
        private static $content;
        /** Sending template content? */
        private static $isHTML = TRUE;


        public static function isAjax() {
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        public static function sendJSON(array $json) {
            self::$content = self::drawJSON($json); //@todo use from json_encode($json, JSON_UNESCAPED_UNICODE);
            self::$isHTML = FALSE;
        }
        
        
        public static function sendHTML($html) {
            self::$content .= $html;
        }
                
        
        public static function getContent() {
            return self::$content;
        }
        
        
        public static function isHTML() {
            return self::$isHTML;
        }     
        
        
        private static function drawJSON(array $array) {
            $out = array();
            
            foreach($array as $key => $val) {
                if(is_array($val)) {
                    $out[] = '"' . $key . '" : ' . self::drawJSON($val);
                } else {
                    $out[] = '"' . $key . '" : "' . $val . '"';
                }
            }
            
            return '{' . implode(', ', $out) . '}';
        }
    }