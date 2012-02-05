<?php
    /**
     *
     *
     * @category   Yucat
     * @package    Admin
     * @name       Login
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.7
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin;
    
    use inc\Cookie;
    use inc\Security;
    
    class Login extends \Model\BaseModel {
        public function login($username, $password, $remember = FALSE) {
            $result = $this->db()
                    ->tables('users')
                    ->select('id')
                    ->where('user', $username)
                    ->where('passwd', Security::password($password))
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
                                'value' => Cookie::_init()->getParam('loggedNumber', $cid->CID) + 1
                            ));
                    
                    Cookie::_init()->setCookie(Cookie::_init()->getHash($cid->CID), $time);
                } else {
                    Cookie::_init()->addParam(Cookie::_init()->myCid, 'UID', $result->id);
                    Cookie::_init()->addParam(Cookie::_init()->myCid, 'loggedNumber', '1');                
                }
                return $result->id;
            }
        }
        
        
        public function resetPassword($mail) {
            $data = $this->db()
                    ->tables('users')
                    ->where('email', $mail)
                    ->fetch();
            
            if($data) {
                $hash = \inc\String::keyGen(256);
                $pass = \inc\String::keyGen(8);
                
                $this->db()
                        ->tables('lost_passwords')
                        ->where('UID', $data->id)
                        ->delete();
                
                $this->db()
                        ->tables('lost_passwords')
                        ->insert(array('UID' => $data->id, 'hash' => $hash, 'passwd' => Security::password($pass)));
                //@todo este pridat funkciu na poslatie mailu....
                //\inc\Mail::send('Support@gshost.eu', 'Bloodman@gshost.eu', 'Password recovery', 'Ak chcete zmenit vase heslo tak kliknite na <a href="' . DOMAIN . '/' . $hash . '">TENTO</a> link.');
                return 1;
            } else {
                return 0;
            }
            
        }
        
        
        public function logout() {
            $cookie = Cookie::_init();
                        
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