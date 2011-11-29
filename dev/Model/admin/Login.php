<?php
    /**
     * Authentification - login
     *
     * @category   Yucat
     * @package    Model
     * @name       Login
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Model\admin;
    
    class Login extends \Model\BaseModel {
        
        public function login($username, $password, $remember = FALSE) {
            $result = $this->db()
                    ->tables('users')
                    ->select('id, password')
                    ->where('username', $username)
                    ->where('password', $password)
                    ->limit(1)
                    ->fetch();

            if($result) { 
                $time = $remember ? time() + 31104000 : 0;
                
                $cookie = new \inc\Cookie();
                $cid = $db->tables('cookie_params')
                    ->select('CID')
                    ->where('name', 'UID')
                    ->where('value', $uid)
                    ->fetch();
            
                if($xists) {
                    if($cookie->cookieHash) {
                        $cookie->deleteHash($cookie->cookieHash);
                    }

                    //Set +1 to loggedNumber
                    $n = $db->tables('cookie_params')
                            ->select('CID')
                            ->where('CID', $cid)
                            ->fetch();
                    $db->tables('cookie_params')->where('CID', $cid)->update(array('CID' => $n + 1));

                    $hash = $db->tables('cookie')
                            ->select('hash')
                            ->where('id', $cid)
                            ->fetch()
                            ->hash;
                    $cookie->setCookie($hash, $time);
                } else {
                    if(!$cookie->cookieHash) {
                        $cookie->addHash($time);
                    }

                    $this->addParam($cookie->getCid($cookie->cookieHash), 'UID', $uid);
                    $this->addParam($cookie->getCid($cookie->cookieHash), 'loggedNumber', '1');                
                }
            }           
        }
        
        public function logout() {
            $cookie = new \inc\Cookie();
            $cookie->logout($_COOKIE['HASH']);
                
            setcookie('id', NULL, 0, '/', DOMAIN);
            setcookie('password', NULL, 0, '/', DOMAIN);
        }
    }