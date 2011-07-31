<?php
    /**
     * Base presenter for main functions for this system
     * 
     * @category   Yucat
     * @package    Presenter
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
        //Resource of DB connection
        private $db;
        
        public function __construct() {
            if(!is_object($this->template)) {
                $this->template = new \stdClass;
            }
            
            $this->template->isLogged       = UID;
            $this->template->isAjax         = \inc\Ajax::isAjax();
            $this->template->__THEME_DIR    = STYLE_DIR . STYLE . '/theme/';
            $this->template->__KEYWORDS     = CFG_TMLP_KEYWORDS;
            $this->template->__DESCRIPTION  = CFG_TMLP_DESCRIPTION;

            global $db;
            $this->db = $db; 
        }
        
        public function db() {
            return $this->db;
        }
        
        public function getVar() {
            return $this->template;
        }
        
        public function getTemplate() {
            return $this->template;
        }
    }