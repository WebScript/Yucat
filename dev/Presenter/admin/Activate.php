<?php
    /**
     * Activate user account
     *
     * @category   Yucat
     * @package    Admin
     * @name       Activate
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter\admin;
    
    use inc\Router;
    
    class Activate extends \Presenter\BasePresenter {
                
        public function __construct() {
            parent::__construct();
            $this->forNotLogged();
            if(!Router::_init()->getParam('method')) {
                Router::redirect('Login');
            }
        }
        
        
        public function activate($hash = '') {
            $this->forNotLogged();
            $activate = new \Model\admin\Activate();
            
            if($activate->Activate($hash)) {
                $this->template->message = '<div class="msg-ok"><h4>Success message</h4>Vas ucet bol aktivovany!</div>';
            } else {
                $this->template->message = '<div class="msg-error"><h4>Error message</h4>Vas ucet sa nepodarilo aktivovat, kontaktujte technicku podporu!</div>';
            }
        }
        
    }