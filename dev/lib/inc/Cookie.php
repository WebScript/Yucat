<?php
    /**
     * Cookie is clas for manage cookie data.
     *
     * @category   Yucat
     * @package    Includes
     * @name       Cookie
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.0
     */

    namespace inc;

    class Cookie {      
        
        public function __construct() {}
        
        
        public static function isLogged($hash) {
            GLOBAL $db;
            return $db->tables('cookie')->where('hash', $hash)->fetch()->UID;
        }
        
        
        public function login($uid, $time) {
            GLOBAL $db;
            $d = $db->tables('cookie')
                    ->where('UID', $uid)
                    ->fetch();
            $hash = isset($d->hash) ? $d->hash : NULL;
            
            if(!$hash) {
                $chars = "1234567890QWERTZUIOPLKJHGFDSAYXCVBNM";
                for($i=0;$i<256;$i++) {
                    $hash .= $chars[rand(0, strlen($chars)-1)];
                }
                $db->tables('cookie')->insert(array('UID' => $uid, 'hash' => $hash, 'logged_number' => '1'));
            } else {
                $db->tables('cookie')->where('hash', $hash)->update(array('logged_number' => $d->logged_number + 1));
            }
            
            setcookie('HASH', $hash, $time, '/', DOMAIN);
            return 1;
        }
        
        
        public function logout($hash) {
           GLOBAL $db;
           setcookie('HASH', NULL, 0, '/', DOMAIN);
            
            $d = $db->tables('cookie')
                    ->where('hash', $hash)
                    ->fetch()
                    ->logged_number;
            
            if($d <= 1) {
                $db->tables('cookie')->where('hash', $hash)->delete();
            } else {
                $db->tables('cookie')->where('hash', $hash)->update(array('logged_number' => $d - 1));
            }
            return 1;
        }
    }