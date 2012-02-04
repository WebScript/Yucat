<?php

    namespace Presenter\admin;
    
    use inc\Form;
    
    class Register extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            GLOBAL $lang;
            $this->forNotLogged();
            
            $form = new Form();
            $form->setAction('Register');
            $form->setMethod('POST');
            
            $form->addElement('login', 'text')
                    ->setLength(4, 14)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $form->addElement('firstname', 'text')
                    ->setLength(3, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $form->addElement('lastname', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $form->addElement('password', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $form->addElement('password2', 'text')
                    ->setLength(4, 30)
                    ->setType('RE')
                    ->setValue('password')
                    ->setErrorMessage('error');
            
            $form->addElement('email', 'text')
                    ->setLength(5, 30)
                    ->setType('EMAIL')
                    ->setErrorMessage('error');
            
            $form->addElement('email2', 'text')
                    ->setLength(5, 30)
                    ->setType('RE')
                    ->setValue('email')
                    ->setErrorMessage('error');
            
            $form->addElement('street', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $form->addElement('language', 'select', $lang->getAvaiableLang())
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $form->addElement('city', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
         
             $form->addElement('postcode', 'text')
                    ->setLength(4, 15)
                    ->setType('NUMBER')
                    ->setErrorMessage('error');

            $form->addElement('telephone', 'text')
                    ->setLength(4, 30)
                    ->setType('TELEPHONE')
                    ->setErrorMessage('error');

            $form->addElement('website', 'text')
                    ->setLength(4, 30)
                    ->setType('WEBSITE')
                    ->setErrorMessage('error');

            $form->addElement('save', 'submit')
                    ->setValue('Registrovat');
            
            
            $this->template->form = $form->sendForm();
        }
    }