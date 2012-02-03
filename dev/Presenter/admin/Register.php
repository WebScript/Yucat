<?php

    namespace Presenter\admin;
    
    use inc\Form;
    
    class Register extends \Presenter\BasePresenter {
        
        public function __construct() {
            $this->forNotLogged();
            
            $form = new Form();
            
            $form->addElement('login', 'text')
                    ->setLength(4, 14)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $form->addElement('firstname', 'text')
                    ->setLength(3, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $form->addElement('lastname', 'text')
                    ->setLength(3, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
            
            $form->addElement('email', 'text')
                    ->setLength(5, 30)
                    ->setType('EMAIL')
                    ->setErrorMessage('error');
            
            $form->addElement('login', 'text')
                    ->setLength(4, 14)
                    ->setType('TEXT')
                    ->setErrorMessage('error');
        }
    }