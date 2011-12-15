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
     * @version    Release: 0.1.8
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc;

    class Cookie {
        
        public $myCid;
        
        public final function __construct() {
            $this->myCid = $this->getCid($this->getMyHash());

            $uid = $GLOBALS['db']->tables('cookie_params')
                    ->select('value')
                    ->where('CID', $this->myCid)
                    ->where('param', 'UID')
                    ->fetch();
            
            define('UID', $uid ? $uid->value : NULL);
        }
        
        
        
        public final function getCid($hash) {
            $id = NULL;
            if($hash) {
                $id = $GLOBALS['db']->tables('cookie')
                        ->select('id')
                        ->where('hash', $hash)
                        ->fetch();
                return $id ? $id->id : 0;
            } else {
                return 0;
            }
        }
        
        
        
        public final function getMyHash() {
            return isset($_COOKIE['HASH']) ? $_COOKIE['HASH'] : NULL;
        }
        
        
        
        public final function getParam($name, $cid = NULL) {
            GLOBAL $db;
            
            if(!$cid) {
                $cid = $this->myCid;
            }
            $value = $db->tables('cookie_params')
                    ->select('value')
                    ->where('CID', $cid)
                    ->where('param', $name)
                    ->fetch();
            
            return $value ? $value->value : NULL;
        }



        public final function addHash($time = 1353044444) { 
            GLOBAL $db;
            
            if(!$db->tables('cookie')->select('id')->where('hash', $this->myCid)->fetch()) {
                while(1) {
                    $hash = '';
                    $chars = '1234567890QWERTZUIOPLKJHGFDSAYXCVBNM';
                    for($i=0;$i<256;$i++) {
                        $hash .= $chars[rand(0, strlen($chars)-1)];
                    }

                    if($db->tables('cookie')->select('id')->where('hash', $hash)->fetch()) {
                        continue;
                    } else {
                        $db->tables('cookie')->insert(array('hash' => $hash));
                        $this->setCookie($hash, $time);
                        break;
                    }
                }
                return $db->tables('cookie')->select('id')->where('hash', $hash)->fetch()->id;
            } else {
                return;
            }
        }
        
        
        
        public final function deleteHash($cid) {
            GLOBAL $db;
                       
            $db->tables('cookie_params')->where('CID', $cid)->delete();
            $db->tables('cookie')->where('id', $cid)->delete();
            $this->setCookie(NULL, 0);
        }
        
        
        
        public final function addParam($cid, $name, $value) {
            GLOBAL $db;

            if(!$cid) {
                $cid = $this->addHash();
            }
            
            $get = $db->tables('cookie_params')
                    ->select('id')
                    ->where('CID', $cid)
                    ->where('param', $name)
                    ->fetch();
            
            if($get) {
                $db->tables('cookie_params')
                        ->where('CID', $cid)
                        ->where('param', $name)
                        ->update(array('value' => $value));
            } else {
                $db->tables('cookie_params')
                        ->insert(array('CID' => $cid, 'param' => $name, 'value' => $value));
            }
        }
        
        
        
        public final function deleteParam($cid, $name) {
            $GLOBALS['db']->tables('cookie_params')->where('CID', $cid)->where('param', $name)->delete();
        }


        
        public final function setCookie($hash, $time) {
            $this->myCid = $this->getCid($hash);
            setcookie('HASH', $hash, $time, '/', DOMAIN);
        }
    }