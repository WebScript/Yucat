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
                    ->select('id')
                    ->where('user', $username)
                    ->where('passwd', $password)
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
                    $cookie->setCookie($hash->hash, $time); // @todo hlasi ze $cookie neni objekt
                } else {
                    $cookie->addParam($cookie->myCid, 'UID', $result->id);
                    $cookie->addParam($cookie->myCid, 'loggedNumber', '1');                
                }
                return 1;
            }
        }
        
        public function logout() {
            GLOBAL $cookie;
                        
            $cid = $cookie->myCid;
            $n = $cookie->getParam('loggedNumber');
            
            if($n <= 1) {
                $cookie->deleteParam($cid, 'loggedNumber');
                $cookie->deleteParam($cid, 'UID');
            } else {
                $db->tables('cookie_params')
                        ->where('CID', $cid)
                        ->update(array('loggedNumber' => $n - 1));
            }
        }
    }