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
            \inc\Router::redirect('User:Profile:profile');
                
            //\inc\Ajax::sendHTML(\Model\Menu::createMenu($menu, $this->template));
        }
        
        public function profile() {
            //echo '123';
        }
        
    }