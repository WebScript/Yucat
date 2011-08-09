<?php
    /**
     * User - Profile
     *
     * @category   Yucat
     * @package    Presenter\User
     * @name       User
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\User;
    
    class Profile extends \Presenter\BasePresenter {
        private $form;
        private $pass;
        
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            \inc\Router::redirect('User:Profile:news', TRUE);
            
            
            $this->form = new \inc\Form();
            $this->form->setAction('User:Profile');
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
            
            $this->form->addElement('address', 'address', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->address)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('error');
            
            $this->form->addElement('language', 'language', 'text')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setValue($this->template->user->lastname)
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
            
            
            
            $this->pass = new \inc\Form();
            $this->pass->setAction('User:Profile');
            $this->pass->setMethod('POST');
            
            $this->pass->addElement('oldpass', 'oldpass', 'password')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('error');
            
            $this->pass->addElement('newpass', 'newpass', 'password')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('error');
            
            $this->pass->addElement('retrypass', 'retrypass', 'password')
                    ->setMinLenght(4)
                    ->setMaxLenght(30)
                    ->setErrorType('TEXT')
                    ->setErrorMessage('error');
            
            $this->pass->addElement('change', 'change', 'submit')
                    ->setValue('Zmenit');
        }
        
        
        public function profile() {
            switch($this->isLogged()->rank) {
                case 0: 
                    $this->template->rank = '<b>' . $this->template->_RANK_0 . '</b>';
                    break;
                case 1: 
                    $this->template->rank = '<b>' . $this->template->_RANK_1 . '</b>';
                    break;
                case 2: 
                    $this->template->rank = '<b>' . $this->template->_RANK_2 . '</b>';
                    break;
                case 3: 
                    $this->template->rank = '<b>' . $this->template->_RANK_3 . '</b>';
                    break;
                case 4: 
                    $this->template->rank = '<b>' . $this->template->_RANK_4 . '</b>';
                    break;
                case 5: 
                    $this->template->rank = '<b>' . $this->template->_RANK_5 . '</b>';
                    break;
            }
            
            $this->template->peer_day = 0;
            $srvs = $this->db()->tables('servers')->where('UID', UID)->fetchAll();
            foreach($srvs as $val) {
                switch($val->type) {
                    case 'SAMP':
                        $this->template->peer_day += $val->slots * COST_SAMP / 50 / 30;
                        break;
                }
            }

            $this->template->form = $this->form->sendForm();
            $this->template->pass = $this->pass->sendForm();
            
            \inc\Ajax::setMode(TRUE);
        }
        
        
        public function news() {
            $this->template->updates        = $this->db()->tables('messages')->where('type', '2')->limit(10)->order('id DESC')->fetchAll();
            $this->template->notifications  = $this->db()->tables('messages')->where('type', '1')->limit(10)->order('id DESC')->fetchAll();
            $this->template->news           = $this->db()->tables('messages')->where('type', '0')->limit(10)->order('id DESC')->fetchAll();
            $this->template->db             = $this->db();
            $this->template->date           = new \inc\Date();
            \inc\Ajax::setMode(TRUE);
        }
        
        
        public function check() {
            if(!$this->form->validateData()) {
                \inc\Ajax::sendJSON($this->pass->validateData());
            } else {
                \inc\Ajax::sendJSON($this->form->validateData());
            }
        }




        public function bannery() {
            \inc\Ajax::sendHTML('news bsdfsdsdffla bla xDD');
        }
        public function access() {
            \inc\Ajax::sendHTML('news sdfsdfsdsfsdfsdfsdfdfsbla bla xDD');
        }
        
    }