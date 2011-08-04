<?php
    /**
     * Authentification - login
     *
     * @category   Yucat
     * @package    Model\Auth
     * @name       Login
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Model\Auth;
    
    class Login extends \Model\BaseModel {
        
        public function login($username, $password, $remember = FALSE) {
            $result = $this->db()
                        ->tables('users')
                        ->where('username', $username)
                        ->where('password', $password)
                        ->limit(1)
                        ->fetch();
            
            if($result) {
                $time = $remember ? 31104000 : 0;
                
                //setcookie('id', $result->id, $time, '/', DOMAIN);
                //setcookie('password', $result->password, $time, '/', DOMAIN);
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }