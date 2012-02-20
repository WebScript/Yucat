<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin
     * @name       Register
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.2.0
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin;

    use inc\Date;
    use inc\Config;
    use inc\String;
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
                $hash = String::keyGen(255);
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
                    'll1' => time(),
                    'activate_id' => $hash
                ));
                
                \inc\Mail::send('Support@gshost.eu', $_POST['email'], 'Potvrdenie registracie GSHost.eu', 'uspesne ste sa registrovali na GSHost.eu, ak si zelate aktivovat Vas ucet tak kliknite na <a href="' . DOMAIN_URI . '/Activate/activate' . $hash . '">TENTO</a> odkaz.');
                return 1;
            }
        }
    }