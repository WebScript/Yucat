<?php
    /**
     * Server - SAMP - Main
     *
     * @category   Yucat
     * @package    Presenter\Server\SAMP
     * @name       Main
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\admin\Server\SAMP;
    
    class Main extends \Presenter\BasePresenter {
        private $form;
        
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            \inc\Router::like('Server:SAMP:profile');
            
            
            $this->form = new \inc\Form();
            $this->form->setAction('Server:SAMP:profile');
            $this->form->setMethod('POST');
           
            $this->form->addElement('firstname', 'firstname', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->firstname)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('error');
            
            $this->form->addElement('lastname', 'lastname', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->lastname)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('error');
            
            $this->form->addElement('email', 'email', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->email)
                    ->setErrorType('EMAIL')
                    ->setErrorMessage('error');
            
            $this->form->addElement('street', 'street', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->street)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('error');
            
            $this->form->addElement('language', 'language', 'select', $GLOBALS['lang']->getAvaiableLang())
                    //->setValue(array('bb' => 'BBBBf'))
                    ->setErrorType('TEXT')
                    ->setErrorMessage('error');
            
            $this->form->addElement('city', 'city', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->city)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('error');
            
            $this->form->addElement('postcode', 'postcode', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->postcode)
                    ->setErrorType('NUMBER')
                    ->setErrorMessage('error');
            
            $this->form->addElement('telephone', 'telephone', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->telephone)
                    ->setErrorType('TELEPHONE')
                    ->setErrorMessage('error');
            
            $this->form->addElement('website', 'website', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->website)
                    ->setErrorType('WEBSITE')
                    ->setErrorMessage('error');
            
            $this->form->addElement('save', 'save', 'submit')
                    ->setValue('Odoslat');
        }
        
        public function profile($id) {
            $this->template->form = $this->form->sendForm();
            echo $id;
        }
    }