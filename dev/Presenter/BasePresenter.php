<?php
    /**
     * Base presenter for main functions for this system
     * 
     * @category   Yucat
     * @package    Includes
     * @name       BasePresenter
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace Presenter;
    
    class BasePresenter {
        //Object of variables for translate
        protected $template;
        
        public function db() {
            global $db;
            return $db;
        }
    }