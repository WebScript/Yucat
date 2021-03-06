<?php
    /**
     * Authentification - login
     *
     * @category   Yucat
     * @package    Admin
     * @name       Login
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter\admin;
    
    use inc\Ajax;
    use inc\Dialog;
    use inc\Router;
    use inc\Security;
    
    class Login extends \Presenter\BasePresenter {
        /** @var Form Login form */
        private $form;
        /** @var Form Lost passeword form */
        private $pass;
        
        
        public function __construct() {
            parent::__construct();

            if(Router::_init()->getParam('method') != 'logout') {
                $this->forNotLogged();
            }

                $this->form = new \inc\Form();
                $this->form->setAction('Login:login:login');
                $this->form->setMethod('POST');
                $this->form->setErrorMessage('length', $this->template->_F_WRONG_LENGTH);

                $this->form->addElement('username', 'text')
                        ->setLength(4, 20);

                $this->form->addElement('password', 'password')
                        ->setLength(4, 20);

                $this->form->addElement('remember', 'checkbox');
                $this->form->addElement('login', 'submit')
                        ->setValue($this->template->_ENTER);
                
                
                $this->pass = new \inc\Form();
                $this->pass->setAction('Login:login:mail');
                $this->pass->setMethod('POST');

                $this->pass->addElement('mail', 'text')
                        ->setLength(4, 30, $this->template->_F_WRONG_LENGTH);

                $this->pass->addElement('send', 'submit')
                        ->setValue($this->template->_SEND_PASS);
                

                $this->template->form = $this->form->sendForm();
                $this->template->pass = $this->pass->sendForm();
        }
        
        
        public function login($type = NULL, $act = NULL) {
            $this->forNotLogged();
            
            if(!Ajax::isAjax()) {
                Router::redirect('Login');
            }
            
            if($type == 'login' && $act == 'check') Ajax::sendJSON($this->form->validateData());
            if($type == 'mail' && $act == 'check') Ajax::sendJSON($this->pass->validateData());
            elseif($type == 'mail' && $act == 'send') {
                if($this->pass->isValidData()) { 
                    $model = new \Model\admin\Login();
                    if($model->resetPassword($_POST['mail'])) {
                         new Dialog($this->template->_SENT_PASSWORD);
                    } else {
                        new Dialog($this->template->_WRONG_EMAIL);
                    }
                } else {
                    Ajax::sendJSON($this->pass->validateData($this->template->_WRONG_DATA));
                }
            } elseif($type == 'login' && $act == 'send') { 
                if($this->form->isValidData()) { 
                    $model = new \Model\admin\Login();
                    $login = $model->login($_POST['username'], $_POST['password'], isset($_POST['remember']) ? $_POST['remember'] : NULL);

                    if($login) {
                        \Model\admin\Access::add(0, 'Login', $login);
                        Ajax::sendJSON(array('redirectHead' => Router::traceRoute('User:Main')));
                    } else {
                        $perm = $this->db()
                                ->tables('users')
                                ->select('permissions')
                                ->where('user', $_POST['username'])
                                ->where('passwd', Security::password($_POST['password']))
                                ->fetch();

                        if(!$perm) new Dialog($this->template->_BAD_LOGIN);
                        
                        switch($perm->permissions) {
                            case 0:
                                new Dialog('Nemate aktivovany ucet!!');
                                break;
                            case 1:
                                break;
                            case 2:
                                new Dialog('Vas ucet bol zablokovany!');
                        }
                    }
                } else {
                    new Dialog($this->template->_BAD_LOGIN);
                }
            }
        }  
        
        
        public function logout() {
            $this->forLogged();
            $model = new \Model\admin\Login();
            $model->logout();
            \Model\admin\Access::add(0, 'Logout');
            Ajax::sendJSON(array('redirectHead' => Router::traceRoute('Login')));
        }
    }