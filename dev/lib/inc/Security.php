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
     * @version    Release: 0.1.6
     * @link       http://www.yucat.net/documentation
     * 
     * @todo write documentation for protect
     */

    namespace inc;

    class Security {

        /** This is static class */
        private function __construct() {}
        
        
        /**
         * Check if E-mail already exists and if is correct
         * 
         * @param string $email E-mail
         * @return BOOL
         */
        public static function checkEmail($email) {
            $reg = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@'
                 . '([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';

            if(preg_match($reg, $email)) {
                list($username, $domain) = explode('@', $email);
                if(checkdnsrr($domain, 'MX')) {
                    return TRUE;
                }
            }
            return FALSE;
        }

        
        /**
         * Convert file size from B to KiB/MiB/GiB
         * 
         * @param integer $size size in B
         * @return string size
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
         * 
         * @param string $link url
         * @return string replaced url
         */
        public static function replaceWWW($link) {
            if(SubStr($link, 0, 7) != 'http://' && SubStr($link, 0, 7) != 'https://') {
                $link = 'http://' . $link;
            }
            
            return $link;
        }
        
        
        /**
         * Protect all data before SQL injection AND html special chars
         * 
         * @param mixed $input input data
         * @param BOOL $isInput TRUE = SQL injection / FALSE = HTML special chars
         * @return mixed output data 
         */
        public static function protect($input, $isInput = FALSE) {
            if(is_array($input)) {
                foreach($input as $key => $val) {
                    $input[$key] = trim($val);
                    $input[$key] = $isInput ? mysql_real_escape_string($val) : htmlspecialchars($val);
                }
            } else {
                $input = trim($input);
                $input = $isInput ? mysql_real_escape_string($input) : htmlspecialchars($input);
            }
            return $input;
        }
        
        
        /**
         *  This is special function for hash password
         * 
         * @param string $password input password
         * @return string output hash
         */
        public static function password($password) {
            $passhHash = Config::_init()->getValue('password_hash');
            return md5($passhHash . md5($password . $passhHash) . md5($passhHash));
        }
        
        
        /**
         * Special method for protect all input ($_GET & $_POST)
         * and set other GET variables
         */
        public static function protectInput() {
            /** Protect all input variables */
            self::protect($_POST, TRUE);
            self::protect($_GET, TRUE);

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