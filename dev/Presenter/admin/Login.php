<?php
    /**
     * Authentification - login
     *
     * @category   Yucat
     * @package    Presenter
     * @name       Login
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.4
     */

    namespace Presenter\admin;
    
    use inc\Ajax;
    
    class Login extends \Presenter\BasePresenter {
        private $form;
        private $pass;
        
        
        public function __construct() {
            parent::__construct();
            $method = $GLOBALS['router']->getParam('method');

            if(!isset($method) || 
                    isset($method) && 
                    $method !== 'logout') {
                $this->forNotLogged();
            }

                $this->form = new \inc\Form();
                $this->form->setAction('Login:login:login');
                $this->form->setMethod('POST');

                $this->form->addElement('username', 'username', 'text')
                        ->setMinLenght(4)
                        ->setMaxLenght(20);

                $this->form->addElement('password', 'password', 'password')
                        ->setMinLenght(4)
                        ->setMaxLenght(20);

                $this->form->addElement('remember', 'remember', 'checkbox');
                $this->form->addElement('login', 'login', 'submit')
                        ->setValue($this->template->_ENTER);
                
                
                $this->pass = new \inc\Form();
                $this->pass->setAction('Login:login:mail');
                $this->pass->setMethod('POST');

                $this->pass->addElement('mail', 'mail', 'text')
                        ->setMinLenght(4)
                        ->setMaxLenght(30);

                $this->pass->addElement('send', 'send', 'submit')
                        ->setValue($this->template->_SEND_PASS);
                

                $this->template->form = $this->form->sendForm();
                $this->template->pass = $this->pass->sendForm();
        }
        
        
        public function login($type = NULL, $act = NULL) {
            $this->forNotLogged();
            
            if($type == 'login' && $act == 'check') Ajax::sendJSON($this->form->validateData());
            if($type == 'mail' && $act == 'check') Ajax::sendJSON($this->pass->validateData());
            elseif($type == 'mail' && $act == 'send') {
                if($this->pass->isValidData()) { 
                    $model = new \Model\admin\Login();
                    $model->resetPassword($_POST['mail']);
                } else {
                    Ajax::sendJSON(array_merge($this->pass->validateData(), 
                            array('dialogValue' => 'Chybne vyplnene udaje!')));
                }
            }
            elseif($type == 'login' && $act == 'send') {
                if($this->form->isValidData()) { 
                    $model = new \Model\admin\Login();
                    $login = $model->login($this->form->getValue('username'), 
                            $this->form->getValue('password'), 
                            $this->form->getValue('remember'));

                    if($login) {
                        Ajax::sendJSON(array('redirectHead' => $GLOBALS['router']->traceRoute('User:Main')));
                    } else {
                        Ajax::sendJSON(array('dialogValue' => 'Zadali ste zle meno alebo heslo...'));
                    }
                } else {
                    Ajax::sendJSON(array_merge($this->form->validateData(), 
                            array('dialogValue' => 'Chybne vyplnene udaje!')));
                }
            }
        }  
        
        
        public function logout() {
            $this->forLogged();
            $model = new \Model\admin\Login();
            $model->logout();
            Ajax::sendJSON(array('redirectHead' => $GLOBALS['router']->traceRoute('Login')));
        }
    }