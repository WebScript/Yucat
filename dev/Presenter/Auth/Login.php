<?php
    /**
     * Authentification - login
     *
     * @category   Yucat
     * @package    Presenter\Auth
     * @name       Login
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.2
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\Auth;
    
    class Login extends \Presenter\BasePresenter {
        private $form;
        
        
        public function __construct() {
            parent::__construct();
            
            if($_POST) {
                //echo 'lol';
            }
            
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
        
        
        public function login() {
            if($this->form->isValidData()) {
                \inc\Ajax::sendJSON(array('redirect' => 'http://google.cz/'));
            } else {
                \inc\Ajax::sendJSON($this->form->validateData());
            }
        }
    }