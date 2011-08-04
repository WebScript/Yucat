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
     * @version    Release: 0.0.4
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter;
    
    class Login extends \Presenter\BasePresenter {
        private $form;
        
        
        public function __construct() {
            parent::__construct();
            $this->forNotLogged();
            
            $this->form = new \inc\Form();
            
            $this->form->addElement('username', 'username', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(20)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('nauc se psat debile...');
            
            $this->form->addElement('password', 'password', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(20)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('nauc se psat debile...');
            
            $this->form->addElement('remember', 'remember', 'checkbox');
            $this->form->addElement('login', 'login', 'submit');
            
            $this->template->form = $this->form->sendForm();
        }
        
        
        public function check() {
            \inc\Ajax::sendJSON($this->form->validateData($_GET));
        }
        
        
        public function send() {
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
    }