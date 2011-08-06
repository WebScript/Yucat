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
        
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            # WTF??
           // exit ('sdssd');
            \inc\Router::redirect('User:Profile:profile', TRUE);
                
            //\inc\Ajax::sendHTML(\Model\Menu::createMenu($menu, $this->template));
        }
        
        public function profile() {
            \inc\Ajax::sendHTML('profile bla bla xDD');
             //d(\inc\Ajax::getMode());
            // \inc\Ajax::sendJSON(array('omg' => 'lol'));
            
        }
        
        
        public function news() { //d(\inc\Template\Core::$translate);
            $this->template->updates        = $this->db()->tables('messages')->where('type', '2')->limit(10)->order('id DESC')->fetchAll();
            $this->template->notifications  = $this->db()->tables('messages')->where('type', '1')->limit(10)->order('id DESC')->fetchAll();
            $this->template->news           = $this->db()->tables('messages')->where('type', '0')->limit(10)->order('id DESC')->fetchAll();
            $this->template->db             = $this->db()->tables('users');
            $this->template->date           = new \inc\Date();
            \inc\Ajax::setMode(TRUE);
        }
        public function bannery() {
            \inc\Ajax::sendHTML('news bsdfsdsdffla bla xDD');
        }
        public function access() {
            \inc\Ajax::sendHTML('news sdfsdfsdsfsdfsdfsdfdfsbla bla xDD');
        }
        
    }