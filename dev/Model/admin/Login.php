<?php
    /**
     * Authentification - login
     *
     * @category   Yucat
     * @package    Model
     * @name       Login
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.5
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
                        ->select('CID')
                        ->where('param', 'UID')
                        ->where('value', $result->id)
                        ->fetch();
            
                if($cid) {
                    //Set +1 to loggedNumber
                    $this->db()
                            ->tables('cookie_params')
                            ->where('CID', $cid->CID)
                            ->where('param', 'loggedNumber')
                            ->update(array(
                                'value' => $cookie->getParam('loggedNumber', $cid->CID) + 1
                            ));
                    
                    $cookie->setCookie($cookie->getHash($cid->CID), $time);
                } else {
                    $cookie->addParam($cookie->myCid, 'UID', $result->id);
                    $cookie->addParam($cookie->myCid, 'loggedNumber', '1');                
                }
                return 1;
            }
        }
        
        
        public function resetPassword($mail) {
            $data = $this->db()
                    ->table('users')
                    ->where('mail', $mail)
                    ->fetch();
            
            if($data) {
                $hash = \inc\String::keyGen(256);
                $pass = \inc\String::keyGen(8);
                
                $this->db()
                        ->table('lost_passwords')
                        ->where('UID', $data->id)
                        ->delete();
                
                $this->db()
                        ->table('lost_passwords')
                        ->insert(array('UID' => $data->id, 'hash' => $hash, 'passwd' => $pass));
                //@todo este pridat funkciu na poslatie mailu....
                return 1;
            } else {
                return 0;
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
                $this->db()->tables('cookie_params')
                        ->where('CID', $cid)
                        ->where('param', 'loggedNumber')
                        ->update(array('value' => $n - 1));
                $cookie->setCookie('', 0);
            }
        }
    }