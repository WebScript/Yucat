<?php
    /**
     * Base presenter for main functions for this system
     * 
     * @category   Yucat
     * @package    Presenter
     * @name       BasePresenter
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.5
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace Presenter;
    
    use inc\Arr;
    use inc\Ajax;
    use inc\Router;
    use inc\Template\Core;
    
    class BasePresenter {
        //Object of variables for translate
        protected $template;
        //Resource of DB connection
        private $db;
        
        public function __construct() {
            GLOBAL $db, $router;
            $this->template = Arr::array2Object(Core::$translate);
            $this->db = $db;

            $this->template->isLogged       = $this->isLogged() ? TRUE : NULL;
            $this->template->isAjax         = Ajax::isAjax();
            $this->template->__THEME_DIR    = PROTOCOL . Router::getDomain() . '/styles/' . STYLE . '/' . $GLOBALS['router']->getParam('subdomain') . '/theme/';
            $this->template->__KEYWORDS     = $GLOBALS['conf']['template_keywords'];
            $this->template->__DESCRIPTION  = $GLOBALS['conf']['template_description'];
            $this->template->__COPYRIGHT    = 'Copyright &copy; 2011, <strong>Yucat ' . $GLOBALS['conf']['version'] . '</strong> OpenSource by <strong>Bloodman Arun</strong>';
            
            if($this->isLogged()) {
                $this->template->user       = $this->db->tables('users')->where('id', UID)->fetch();
                
                /** Set SID const */
                $params = $router->getParam('params');
                if(isset($params[0])) {
                    $sid = $this->db()->tables('servers')->select('id')->where('UID', UID)->where('id', $params[0])->fetch();
                    if($sid && !defined('SID')) define('SID', $sid->id ? : NULL);
                }
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
            GLOBAL $cookie;

            $uid = $this->db()->tables('cookie_params')
                    ->where('CID', $cookie->myCid)
                    ->where('param', 'UID')
                    ->fetch();

            return $uid ? $uid->value : NULL;
        }
        
        
        protected function callServer($sid, $connect = FALSE) {
            $val = $this->db->tables('servers, machines')
                    ->select('servers.id, machines.ssh_ip, machines.ssh_port, machines.ssh_login, machines.ssh_password')
                    ->where('servers.UID', UID)
                    ->where('servers.id', $sid)
                    ->where('servers.MID', 'machines.id', TRUE)
                    ->fetch();
            
            //@todo pridat kontrolu ci nema zamknuty server!
            if($val && $connect) {
                $ssh = new \inc\Servers\SecureShell($val->ssh_ip, $val->ssh_port, $val->ssh_login, $val->ssh_password);
            } elseif($val) {
                $ssh = TRUE;
            } else {
                $ssh = FALSE;
                \inc\Diagnostics\ExceptionHandler::Error('Server doesn\'t exists!');
            }
            return $ssh;
        }
        
        
        protected function forNotLogged($url = 'User:Main') {
            if($this->isLogged()) {
                Router::redirect($url);
            }
        }
        
        protected function forLogged($url = 'Login') {
            if(!$this->isLogged()) {
                Router::redirect($url);
            }
        }
        
    }