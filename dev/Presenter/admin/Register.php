<?php

    namespace Presenter\admin;
    
    use inc\Form;
    use inc\Ajax;
    use inc\Dialog;
    
    class Register extends \Presenter\BasePresenter {
        private $form;
        
        
        public function __construct() {
            parent::__construct();
            GLOBAL $lang;
            $this->forNotLogged();
            
            $this->form = new Form();
            $this->form->setAction('Register');
            $this->form->setMethod('POST');
            $this->form->setErrorMessage('length', $this->template->_F_WRONG_LENGTH);
            
            $this->form->addElement('login', 'text')
                    ->setLength(4, 14);
            
            $this->form->addElement('firstname', 'text')
                    ->setLength(3, 30)
                    ->setType('TEXT', $this->template->_F_TEXT_ERR);
            
            $this->form->addElement('lastname', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT', $this->template->_F_TEXT_ERR);
            
            $this->form->addElement('password', 'password')
                    ->setLength(4, 30);
                    //->setType('PASSWORD');
            
            $this->form->addElement('password2', 'password')
                    ->setType('RE', $this->template->_F_PASSWORD2)
                    ->setValue('password');
            
            $this->form->addElement('email', 'text')
                    ->setLength(5, 30)
                    ->setType('EMAIL', $this->template->_F_EMAIL);
            
            $this->form->addElement('email2', 'text')
                    ->setType('RE', $this->template->_F_EMAIL2)
                    ->setValue('email');
            
            $this->form->addElement('street', 'text')
                    ->setLength(4, 30);
            
            $this->form->addElement('language', 'select', $lang->getAvaiableLang());
            
            $this->form->addElement('city', 'text')
                    ->setLength(4, 30);
         
             $this->form->addElement('postcode', 'text')
                    ->setLength(4, 15)
                    ->setType('NUMBER', $this->template->_F_NOT_NUMERIC);

            $this->form->addElement('telephone', 'text')
                    ->setLength(4, 30)
                    ->setType('NUMBER', $this->template->_F_NOT_NUMERIC);

            $this->form->addElement('website', 'text')
                    ->setLength(4, 40);
                    //->setType('WEBSITE');

            $this->form->addElement('save', 'submit')
                    ->setValue($this->template->_F_REGISTRATION);
            
            
            $this->template->form = $this->form->sendForm();
            $this->template->registred = $this->db()->tables('users')->numRows();
            $this->template->hosted = $this->db()->tables('servers')->numRows();
            $this->template->sponsored = $this->db()->tables('servers')->where('lock', '1')->numRows();
        }
        
        
        public function check() {
            Ajax::sendJSON($this->form->validateData());
        }
        
        
        public function send() {
            if($this->form->isValidData()) {
                $register = new \Model\admin\Register;
                switch($register->register()) {
                    case 1:
                        new Dialog($this->template->_REGISTER_SUCCESS, Dialog::DIALOG_SUCCESS);
                        break;
                    case 2:
                        new Dialog($this->template->_USER_EXISTS, Dialog::DIALOG_ERROR);
                        break;
                    case 3:
                        new Dialog($this->template->_IP_LIMIT, Dialog::DIALOG_ERROR);
                        break;
                    case 4:
                        new Dialog($this->template->_EMAIL_EXISTS, Dialog::DIALOG_ERROR);
                        break;
                }
            } else {
                Ajax::sendJSON($this->form->validateData($this->template->_WRONG_DATA));
            }
        }
    }