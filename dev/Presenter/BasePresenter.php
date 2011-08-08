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
     * @version    Release: 0.1.1
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
            $this->template = \inc\Arr::array2Object(\inc\Template\Core::$translate);
            
            global $db;
            $this->db = $db; 
            
            $this->template->isLogged       = $this->isLogged() ? TRUE : NULL;
            $this->template->isAjax         = \inc\Ajax::isAjax();
            $this->template->__THEME_DIR    = STYLE_DIR . STYLE . '/theme/';
            $this->template->__KEYWORDS     = CFG_TMLP_KEYWORDS;
            $this->template->__DESCRIPTION  = CFG_TMLP_DESCRIPTION;
            $this->template->__COPYRIGHT    = 'Copyright &copy; 2011, <strong>Yucat ' . CFG_VERSION . '</strong> GNU GPL v2 by <strong>Bloodman Arun</strong>';
            
            if($this->isLogged()) {
                $this->template->user       = $db->tables('users')->where('id', $_COOKIE['id'])->fetch();
            }
        }
        
        protected function db() {
            return $this->db;
        }
        
        public function getVar() {
            return $this->template;
        }
        
        public function getTemplate() {
            return $this->template;
        }
        
        
        protected function isLogged() {
            if(empty($_COOKIE['id']) || empty($_COOKIE['password'])) {
                return FALSE;
            }
            
            $result = $this->db()
                    ->tables('users')
                    ->where('id', $_COOKIE['id'])
                    ->where('password', $_COOKIE['password'])
                    ->fetch();
            if($result) {
                return $result;
            } else {
                return FALSE;
            }
        }
        
        
        protected function forNotLogged($url = 'User:Profile') {
            if($this->isLogged()) {
                \inc\Router::redirect($url);
            }
        }
        
        protected function forLogged($url = 'Login') {
            if(!$this->isLogged()) {
                \inc\Router::redirect($url);
            }
        }
    }