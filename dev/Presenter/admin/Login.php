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
        
        
        public function __construct() {
            parent::__construct();
            $method = $GLOBALS['router']->getParam('method');

            if(!isset($method) || 
                    isset($method) && 
                    $method !== 'logout') {
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
            Ajax::sendJSON($this->form->validateData());
        }
        
        
        public function loginSend() {
            $this->forNotLogged();
            if($this->form->isValidData()) { 
                $model = new \Model\admin\Login();
                $login = $model->login($this->form->getValue('username'), 
                        $this->form->getValue('password'), 
                        $this->form->getValue('remember'));
                
                if($login) {
                    $GLOBALS['router']->redirect('User:Main');
                } else {
                    Ajax::sendJSON(array('dialogName' => 'Error', 'dialogValue' => 'Zadali ste zle meno alebo heslo...'));
                }
            } else {
                Ajax::sendJSON($this->form->validateData());
            }
        }
        
        
        public function logout() {
            $this->forLogged();
            $model = new \Model\admin\Login();
            $model->logout();
            Ajax::sendJSON(array('redirect' => $GLOBALS['router']->traceRoute('Login')));
        }
    }