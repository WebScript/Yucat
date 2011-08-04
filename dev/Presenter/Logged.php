<?php
    /**
    * Authentification - Logged
    *
    * @category Yucat
    * @package Presenter
    * @name Logged
    * @author René Činčura (Bloodman Arun)
    * @copyright Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
    * @license http://www.yucat.net/license GNU GPL License
    * @version Release: 0.0.2
    * @link http://www.yucat.net/documentation
    * @since Class available since Release 0.0.1
    */

    namespace Presenter;
    
    class Logged extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            
            $this->template->user = $this->isLogged();
        }
    }