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
     * @version    Release: 0.0.4
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Model\admin;
    
    class Login extends \Model\BaseModel {
        
        public function login($username, $password, $remember = FALSE) {
            GLOBAL $cookie;
            
            $result = $this->db()
                    ->tables('users')
                    ->select('id, password')
                    ->where('username', $username)
                    ->where('password', $password)
                    ->fetch();

            if($result) { 
                $time = $remember ? time() + 31104000 : 0;
                
                //Get cookie with exists UID exists
                $cid = $this->db()->tables('cookie_params')
                        ->where('param', 'UID')
                        ->where('value', $result->id)
                        ->fetch();
            
                if($cid) {
                    //Set +1 to loggedNumber
                    $this->db()
                            ->tables('cookie_params')
                            ->where('CID', $cid->id)
                            ->update(array(
                                'loggedNumber' => $cookie->getParam('loggedNumber') + 1
                            ));

                    $hash = $this->db()->tables('cookie')
                            ->select('hash')
                            ->where('id', $cid->id)
                            ->fetch();
                    $cookie->setCookie($hash->hash, $time);
                } else {
                    $cookie->addParam($cookie->myCid, 'UID', $result->id);
                    $cookie->addParam($cookie->myCid, 'loggedNumber', '1');                
                }
                return 1;
            }
        }
        
        public function logout() {
            $cookie = new \inc\Cookie();
            $cookie->logout($_COOKIE['HASH']);
                
            setcookie('id', NULL, 0, '/', DOMAIN);
            setcookie('password', NULL, 0, '/', DOMAIN);
        }
    }