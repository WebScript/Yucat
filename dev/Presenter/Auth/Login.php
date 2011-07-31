<?php
    /**
     * Authentification - login
     *
     * @category   Yucat
     * @package    Presenter\Auth
     * @name       Login
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\Auth;
    
    class Login extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            
            if($_POST) {
                echo 'lol';
            }
            
        }
    }