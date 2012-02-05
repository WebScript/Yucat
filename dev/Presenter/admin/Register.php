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
            
            $this->form->addElement('login', 'text')
                    ->setLength(4, 14)
                    ->setErrorMessage('error');
            
            $this->form->addElement('firstname', 'text')
                    ->setLength(3, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $this->form->addElement('lastname', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $this->form->addElement('password', 'password')
                    ->setLength(4, 30)
                    ->setType('PASSWORD')
                    ->setErrorMessage('error');
            
            $this->form->addElement('password2', 'password')
                    ->setType('RE')
                    ->setValue('password')
                    ->setErrorMessage('error');
            
            $this->form->addElement('email', 'text')
                    ->setLength(5, 30)
                    ->setType('EMAIL')
                    ->setErrorMessage('error');
            
            $this->form->addElement('email2', 'text')
                    ->setType('RE')
                    ->setValue('email')
                    ->setErrorMessage('error');
            
            $this->form->addElement('street', 'text')
                    ->setLength(4, 30)
                    ->setErrorMessage('error');
            
            $this->form->addElement('language', 'select', $lang->getAvaiableLang())
                    ->setErrorMessage('error');
            
            $this->form->addElement('city', 'text')
                    ->setLength(4, 30)
                    ->setErrorMessage('error');
         
             $this->form->addElement('postcode', 'text')
                    ->setLength(4, 15)
                    ->setType('NUMBER')
                    ->setErrorMessage('error');

            $this->form->addElement('telephone', 'text')
                    ->setLength(4, 30)
                    ->setType('NUMBER')
                    ->setErrorMessage('error');

            $this->form->addElement('website', 'text')
                    ->setLength(4, 30)
                    ->setType('WEBSITE')
                    ->setErrorMessage('error');

            $this->form->addElement('save', 'submit')
                    ->setValue('Registrovat');
            
            
            $this->template->form = $this->form->sendForm();
        }
        
        
        public function check() {
            Ajax::sendJSON($this->form->validateData());
        }
        
        
        public function send() {
            if($this->form->isValidData()) {
                $register = new \Model\admin\Register;
                switch($register->register()) {
                    case 1:
                        new Dialog('Registracia prebehla uspesne', Dialog::DIALOG_SUCCESS);
                        break;
                    case 2:
                        new Dialog('Taky uzivatel uz exituje!', Dialog::DIALOG_ERROR);
                        break;
                    case 3:
                        new Dialog('Prekroceny pocet registrovanych uzivatelov z vasej IP adresy!', Dialog::DIALOG_ERROR);
                        break;
                    case 4:
                        new Dialog('Vas email uz bol pouzity pre registracii!', Dialog::DIALOG_ERROR);
                        break;
                }
            } else {
                Ajax::sendJSON($this->form->validateData('Chybne vyplnene udaje!'));
            }
        }
    }