<?php
    /**
     * User - Profile
     *
     * @category   Yucat
     * @package    Presenter\User
     * @name       Main
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.7
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\admin\User;
    
    use inc\Ajax;
    use inc\Date;
    
    class Main extends \Presenter\BasePresenter {

        public function __construct() {
            parent::__construct();
            $this->forLogged();
            \inc\Router::redirect('User:Main:news', TRUE);
        }
        
        
        public function profile($type = NULL, $act = NULL) {
            $form = new \inc\Form();
            $form->setAction('User:Main:profile:data');
            $form->setMethod('POST');

            $form->addElement('firstname', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setValue($this->template->user->firstname)
                    ->setErrorMessage('error');

            $form->addElement('lastname', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setValue($this->template->user->lastname)
                    ->setErrorMessage('error');

            $form->addElement('email', 'text')
                    ->setValue($this->template->user->email);

            $form->addElement('street', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setValue($this->template->user->street)
                    ->setErrorMessage('error');

            $form->addElement('language', 'select', $GLOBALS['lang']->getAvaiableLang())
                    //->setValue(array('bb' => 'BBBBf'))
                    ->setType('TEXT')
                    ->setErrorMessage('error');

            $form->addElement('city', 'text')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setValue($this->template->user->city)
                    ->setErrorMessage('error');

            $form->addElement('postcode', 'text')
                    ->setLength(4, 15)
                    ->setType('NUMBER')
                    ->setValue($this->template->user->postcode)
                    ->setErrorMessage('error');

            $form->addElement('telephone', 'text')
                    ->setLength(4, 30)
                    ->setType('TELEPHONE')
                    ->setValue($this->template->user->telephone)
                    ->setErrorMessage('error');

            $form->addElement('website', 'text')
                    ->setLength(4, 30)
                    ->setType('WEBSITE')
                    ->setValue($this->template->user->website)
                    ->setErrorMessage('error');

            $form->addElement('save', 'submit')
                    ->setValue('Odoslat');
            

            $pass = new \inc\Form();
            $pass->setAction('User:Main:profile:pass');
            $pass->setMethod('POST');

            $pass->addElement('oldpass', 'password')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');

            $pass->addElement('newpass', 'password')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');

            $pass->addElement('retrypass', 'password')
                    ->setLength(4, 30)
                    ->setType('TEXT')
                    ->setErrorMessage('error');

            $pass->addElement('change', 'submit')
                    ->setValue('Zmenit');
                
            
            if($type == 'data' && $act == 'check') Ajax::sendJSON($form->validateData());
            elseif($type == 'pass' && $act == 'check') Ajax::sendJSON($pass->validateData());
            elseif($type == 'data' && $act == 'send') {
                $main = new \Model\admin\User\Main();
                if(!$form->isValidData()) {
                    Ajax::sendJSON($form->validateData('Chybne vyplnene udaje!'));
                } else if($main->saveProfile()) {
                    \Model\admin\Access::add(0, 'save profil');
                    Ajax::sendJSON(array('dialogValue' => 'Profil bol uspesne ulozeny!'));
                } else {
                    Ajax::sendJSON(array('dialogValue' => 'Nepodarilo sa ulozit profil!'));
                }
            } elseif($type == 'pass' && $act == 'send') {
                $main = new \Model\admin\User\Main();
                if(!$pass->isValidData()) {
                    Ajax::sendJSON($pass->validateData('Chybne vyplnene udaje!'));
                    return;
                }

                switch($main->changePassword()) {
                    case 1:
                        \Model\admin\Access::add(0, 'save password');
                        Ajax::sendJSON(array('dialogValue' => 'Heslo bolo ulozene!'));
                        break;
                    case 2:
                        Ajax::sendJSON(array('dialogValue' => 'Zadane hesla sa nerovnaju!'));
                        break;
                    case 3:
                        Ajax::sendJSON(array('dialogValue' => 'Zadali ste nespravne heslo!'));
                        break;
                    default :
                        Ajax::sendJSON(array('dialogValue' => 'Nepodarilo sa ulozit heslo!'));
                        break;
                }
            } else {  
                $rank = new \Model\admin\User\Main();
                $this->template->rank       = $rank->getUserRank($this->template->user->rank, $this->template);
                $this->template->peer_day   = $rank->getCreditPeerDay(UID);
                $this->template->form       = $form->sendForm();
                $this->template->pass       = $pass->sendForm();
                $this->template->date       = new Date();
            }
        }
        
        
        
        public function news() {
            $this->template->updates        = $this->db()->tables('news')->where('position', '2')->limit(10)->order('id DESC')->fetchAll();
            $this->template->notifications  = $this->db()->tables('news')->where('position', '1')->limit(10)->order('id DESC')->fetchAll();
            $this->template->news           = $this->db()->tables('news')->where('position', '0')->limit(10)->order('id DESC')->fetchAll();
            $this->template->db             = $this->db();
            $this->template->date           = new Date();
            $this->template->main           = new \Model\admin\User\Main();
        }
    }