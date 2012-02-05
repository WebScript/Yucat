<?php

    namespace Model\admin;

    use inc\Date;
    use inc\Config;
    use inc\Security;
    
    class Register extends \Model\BaseModel {
        
        public function register() {
            if($this->db()->tables('users')->where('user', $_POST['login'])->fetch()) {
                return 2;
            } else if($this->db()->tables('users')->where('ip', UIP)->numRows() >= 5) {
                return 3;
            } else if($this->db()->tables('users')->where('email', $_POST['email'])->fetch()) {
                return 4;
            } else {
                $this->db()->tables('users')->insert(array(
                    'user' => $_POST['login'],
                    'passwd' => Security::password($_POST['password']),
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'street' => $_POST['street'],
                    'city' => $_POST['city'],
                    'postcode' => $_POST['postcode'],
                    'telephone' => $_POST['telephone'],
                    'credit' => Config::_init()->getValue('gift_credit'),
                    'language' => $_POST['language'],
                    'email' => $_POST['email'],
                    'website' => $_POST['website'],
                    'ip' => UIP,
                    'll1' => Date::toMysqlTime(time())
                ));
                return 1;
            }
        }
    }