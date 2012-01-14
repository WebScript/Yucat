<?php
    /**
     * Cookie is clas for manage cookie data.
     *
     * @category   Yucat
     * @package    Library
     * @name       Cookie
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.2.1
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;

    class Cookie {
        /** @var integer My cookie ID */
        public $myCid;
        
        
        /**
         * Set $myCid to my cookie ID and set UID if is set
         */
        public final function __construct() {
            $this->myCid = $this->getCid($this->getMyHash());

            $uid = $GLOBALS['db']->tables('cookie_params')
                    ->select('value')
                    ->where('CID', $this->myCid)
                    ->where('param', 'UID')
                    ->fetch();
            
            define('UID', $uid ? $uid->value : NULL);
        }
        
        
        /**
         * Get Cookie ID by HASH
         * 
         * @param string $hash HASH
         * @return integer my cookie ID
         */
        public final function getCid($hash) {
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
        
        
        /**
         * Get my HASH from $_COOKIE
         * 
         * @return string My HASH
         */
        public final function getMyHash() {
            return isset($_COOKIE['HASH']) ? $_COOKIE['HASH'] : NULL;
        }
        
        
        /**
         * Get HASH by Cookie ID
         * 
         * @param integer $id Cookie ID
         * @return string HASH
         */
        public final function getHash($id) {
            $hash = $GLOBALS['db']->tables('cookie')
                    ->select('hash')
                    ->where('id', $id)
                    ->fetch();
            return isset($hash->hash) ? $hash->hash : NULL;
        }
        
        
        /**
         * Get params from database by param name and CID
         * if CID is not set so is used my CID
         * 
         * @param string $name name of param
         * @param integer $cid Cookie id
         * @return string value from db
         */
        public final function getParam($name, $cid = NULL) {          
            if(!$cid) {
                $cid = $this->myCid;
            }
            $value = $GLOBALS['db']->tables('cookie_params')
                    ->select('value')
                    ->where('CID', $cid)
                    ->where('param', $name)
                    ->fetch();
            
            return $value ? $value->value : NULL;
        }


        /**
         * Add random HASH to DB and set to my $_COOKIE
         * 
         * @param integer $time time() stamp of time. If isn't specified no time so is uset time for 365 days
         * @return 
         */
        public final function addHash($time = 1353044444) { 
            GLOBAL $db;
            
            if(!$db->tables('cookie')->select('id')->where('hash', $this->myCid)->fetch()) {
                while(1) {
                    $hash = String::keyGen(256);

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
                return NULL;
            }
        }
        
        
        /**
         * Delete HASH from DB by CID and set $_COOKIE to NULL
         *
         * @param integer $cid Cookie ID
         */
        public final function deleteHash($cid) {
            GLOBAL $db;
                       
            $db->tables('cookie_params')->where('CID', $cid)->delete();
            $db->tables('cookie')->where('id', $cid)->delete();
            $this->setCookie(NULL, 0);
        }
        
        
        /**
         * Add param to DB by CID
         * 
         * @param integer $cid Cookie ID, if $cid is NULL so is used my CID
         * @param string $name Name of param
         * @param string $value Value of param
         */
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
        
        
        /**
         * Delete param from DB by CID and name of param
         * 
         * @param integer $cid Cookie ID
         * @param string $name Name of param
         */
        public final function deleteParam($cid, $name) {
            $GLOBALS['db']->tables('cookie_params')->where('CID', $cid)->where('param', $name)->delete();
        }


        /**
         * Set cookie's hash to $_COOKIE and to $myCid
         * 
         * @param string $hash HASH
         * @param integer $time time for setcookie()
         */
        public final function setCookie($hash, $time) {
            $this->myCid = $this->getCid($hash);
            setcookie('HASH', $hash, $time, '/', DOMAIN);
        }
    }