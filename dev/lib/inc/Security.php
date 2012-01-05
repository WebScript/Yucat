<?php
    /**
     * This is main class for securing and hashing.
     *
     * @category   Yucat
     * @package    Library
     * @name       Security
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.3
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;

    class Security {
        private function __construct() {}
        
        
        /**
         * Check if E-mail already exists and if is correct
         * 
         * @param string $email E-mail
         * @return BOOL
         */
        public static final function checkEmail($email) {
            $reg = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@'
                 . '([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
            
            list($username, $domain) = explode('@', $email);
            if(!preg_match($reg, $email) || !checkdnsrr($domain, 'MX')){
                return FALSE;
            }
            return TRUE;
        }

        
        /**
         * Convert file size from B to KiB/MiB/GiB
         * 
         * @param integer $size size in B
         * @return string size
         */
        public static final function getFileSize($size) {
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
         * 
         * @param string $link url
         * @return string replaced url
         */
        public static final function replaceWWW($link) {
            if(SubStr($link, 0, 7) != 'http://' && SubStr($link, 0, 7) != 'https://') {
                $link = 'http://' . $link;
            }
            
            return $link;
        }
        
        
        /**
         * Protect string, mysql escape or special chars
         * 
         * @param string $string input string
         * @param BOOL $isInput is input
         * @return string output string
         */
        public static final function protect($string, $isInput = FALSE) {
            $string = trim($string);
            
            if($isInput) {
                $string = mysql_real_escape_string($string);
            } else {
                $string = str_replace(
                        array('<', '>', '\\\'', '\\\"'), 
                        array('&gt;', '&lt;', '\'', '"'), 
                        $string);
            }
            
            return $string;
        }
        
        
        /**
         * protectArray is method for protect array (alias for protect + foreach
         * array)
         * 
         * @param array $array input array
         * @param type $isInput is input
         * @return type output array
         */
        public static final function protectArray(array $array, $isInput = FALSE) {
            foreach($array as $key => $val) {
                $array[$val] = self::protect($val, $isInput);
            }
            return $array;
        }
        
        
        /**
         *  This is special function for hash password
         * 
         * @param string $password input password
         * @return string output hash
         */
        public static final function hashPassword($password) {
            $passhHash = $GLOBALS['conf']['password_hash'];
            return md5($passhHash . md5($password . $passhHash) . md5($passhHash));
        }
        
        
        /**
         * Special method for protect all input ($_GET & $_POST)
         * and set other GET variables
         */
        public static final function protectInput() {
            /** Protect all input variables */
            self::protectArray($_POST, TRUE);
            self::protectArray($_GET, TRUE);

            /** Set variables for pager */
            if(!empty($_GET['select-view']) && is_numeric($_GET['select-view'])) {
                $_GET['peerPage'] = $_GET['select-view'];
            } else {
                if(empty($_GET['peerPage']) || !is_numeric($_GET['peerPage'])) {
                    $_GET['peerPage'] = 25;
                }
            }
            /** And this */
            $_GET['page'] = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;   
        }
    }