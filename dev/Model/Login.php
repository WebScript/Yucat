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

    namespace Model;
    
    class Login extends \Model\BaseModel {
        
        public function login($username, $password, $remember = FALSE) {
            $result = $this->db()
                        ->tables('users')
                        ->where('username', $username)
                        ->where('password', $password)
                        ->limit(1)
                        ->fetch();
            
            if($result) { 
                $time = $remember ? time() + 31104000 : 0;
                setcookie('id', $result->id, $time, '/', DOMAIN);
                setcookie('password', $result->password, $time, '/', DOMAIN);
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        public function logout() {
            setcookie('id', NULL, 0, '/', DOMAIN);
            setcookie('password', NULL, 0, '/', DOMAIN);
        }
    }