<?php
    /**
     * Authentification - login
     *
     * @category   Yucat
     * @package    Presenter
     * @name       Login
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.8
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.4
     */

    namespace Presenter;
    
    class Login extends \Presenter\BasePresenter {
        private $form;
        
        
        public function __construct() {
            parent::__construct();
            $router = \inc\Router::getOnlyAddress();

            if(!isset($router['method']) || 
                    isset($router['method']) && 
                    $router['method'] !== 'logout') {
                $this->forNotLogged();
            }

                $this->form = new \inc\Form();
                $this->form->setAction('Login:login');
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

                $this->template->form = $this->form->sendForm();
        }
        
        
        public function loginCheck() {
            $this->forNotLogged();
            \inc\Ajax::sendJSON($this->form->validateData());
        }
        
        
        public function loginSend() {
            $this->forNotLogged();
            if($this->form->isValidData()) { 
                $model = new \Model\Login();
                $login = $model->login($this->form->getValue('username'), 
                        $this->form->getValue('password'), 
                        $this->form->getValue('remember'));
                
                if($login) {
                    \inc\Ajax::sendJSON(array('redirect' => \inc\Router::traceRoute('User:Profile')));
                } else {
                    \inc\Ajax::sendJSON(array('alert' => 'Zadali ste zle meno alebo heslo...'));
                }
            } else {
                \inc\Ajax::sendJSON($this->form->validateData());
            }
        }
        
        
        public function logout() {
            $this->forLogged();
            $model = new \Model\Login();
            $model->logout();
            \inc\Ajax::sendJSON(array('redirect' => \inc\Router::traceRoute('Login')));
        }
    }