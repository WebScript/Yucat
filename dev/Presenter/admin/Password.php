<?php
    /**
     * Recovery lost password
     *
     * @category   Yucat
     * @package    Admin
     * @name       Password
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter\admin;
    
    use inc\Router;
    
    class Password extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            $this->forNotLogged();
            if(!Router::_init()->getParam('method')) {
                Router::redirect('Login');
            }
        }
        
        public function recovery($hash = '') {
            $this->forNotLogged();
            $pass = new \Model\admin\Password();
            
            if($pass->recovery($hash)) {
                $this->template->message = '<div class="msg-ok"><h4>Success message</h4>Vase nove heslo Vam bolo zaslane na E-mail!</div>';
            } else {
                $this->template->message = '<div class="msg-error"><h4>Error message</h4>Nepodarilo sa obnovit Vase heslo!!</div>';
            }
        }
        
    }