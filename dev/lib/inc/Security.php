<?php
    /**
     * This is main class for securing and hashing.
     *
     * @category   Yucat
     * @package    Includes
     * @name       Security
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc;

    class Security {
        
        /** This variable is added to passwrod and is hashed */
        const PASSWORD_HASH = ''; //45E85G1H8UJ';
        
        private function __construct() {}
        
        
        /**
         * Check if E-mail already exists and if is correct
         * @param string $email E-mail
         * @return BOOL
         */
        public static function checkEmail($email) {
            $reg = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@'
                 . '([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
            
            list($username, $domain) = explode('@', $email);
            if(!preg_match($reg, $email) || !checkdnsrr($domain, 'MX')){
                return FALSE;
            }
            return TRUE;
        }

        
        /**
         * Convert file size from B to KB/MB/GB
         * @param integer $size size in B
         * @return string 
         */
        public static function getFileSize($size) {
            if(is_numeric($size)) {
                if($size >= 1073741824) $size = round($size/1073741824*100)/100 . ' GB';
                elseif($size >= 1048576) $size = round($size/1048576*100)/100 . ' MB';
                elseif($size >= 1024) $size = round($size/1024*100)/100 . ' KB';
                else $size = $size . ' B';
                return $size;
            }
        }


        /**
         * Replace URL and add http://
         * @param string $link
         * @return string 
         */
        public static function replaceWWW($link) {
            if(SubStr($link, 0, 7) != 'http://' && SubStr($link, 0, 7) != 'https://') {
                $link = 'http://'.$link;
            }
            
            if(SubStr($link, -1) != '/') {
                $link .= '/';
            }
            
            return $link;
        }
        
        
        /**
         * Protect string, mysql escape or special chars
         * @param string $string
         * @param BOOL $isInput
         * @return string 
         */
        public static function protect($string, $isInput) {
            $out = FALSE;
            
            if($isInput) {
                $out = mysql_real_escape_string($string);
            } else {
                $out = str_replace(
                        array('<', '>', '\\\'', '\\\"'), 
                        array('&gt;', '&lt;', '\'', '"'), 
                        $string);
            }
            
            return $out;
        }
        
        
        /**
         * Create a hash of password
         * @param string $password
         * @return string
         */
        public static function createHash($password) {
            return md5($password . self::PASSWORD_HASH);
        }
    }