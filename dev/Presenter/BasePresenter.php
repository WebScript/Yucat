<?php
    /**
     * Base presenter for main functions for this system
     * 
     * @category   Yucat
     * @package    Presenter
     * @name       BasePresenter
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.5
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter;
    
    use inc\Db;
    use inc\Arr;
    use inc\Ajax;
    use inc\Config;
    use inc\Router;
    use inc\Template\Core;
    use inc\Diagnostics\Excp;
    use inc\Servers\SecureShell;
    
    class BasePresenter {
        /** @var object Object of variables for translate */
        protected $template;
        
        
        public function __construct() {
            $this->template = Arr::array2Object(Core::$translate);

            $this->template->isLogged       = UID ? TRUE : FALSE;
            $this->template->isAjax         = Ajax::isAjax();
            $this->template->__THEME_DIR    = PROTOCOL . Router::getDomain() . '/styles/' . STYLE . '/' . Router::_init()->getParam('subdomain') . '/theme/';
            $this->template->__KEYWORDS     = Config::_init()->getValue('template_keywords');
            $this->template->__DESCRIPTION  = Config::_init()->getValue('template_description');
            $this->template->__COPYRIGHT    = 'Copyleft &copy; 2011 - 2012, <strong>Yucat ' . Config::_init()->getValue('version') . ' Beta</strong> OpenSource GPLv3 by <strong>Bloodman Arun</strong>';
            
            if(UID) {
                /* Set SID const */
                $params = Router::_init()->getParam('params');
                if(isset($params[0]) && is_numeric($params[0])) {
                    $sid = $this->db()->tables('servers')->select('id')->where('UID', UID)->where('id', $params[0])->fetch();
                    if(!defined('SID')) {
                        if($sid && $sid->id) define('SID', $sid->id);
                        else new Excp('E_ISE', 'E_WRONG_SID');
                    }
                }
            }
        }
        
        
        protected function db() {
            return Db::_init();
        }
        
        
        protected function user() {
            return UID ? new \obj\User(UID) : NULL;
        }
        
        
        public function getTemplate() {
            return $this->template;
        }
        
        
        protected function callServer($sid, $connect = FALSE) {
            $val = $this->db()->tables('servers, machines')
                    ->select('servers.id, servers.permissions, machines.ssh_ip, machines.ssh_port, machines.ssh_login, machines.ssh_password')
                    ->where('servers.id', $sid)
                    ->where('servers.MID', 'machines.id', TRUE)
                    ->fetch();
            
            //Pridat kontrolu ze ak server neni jeho tak ho to vrati pekne spet
            
            if($val) {
                switch($val->permissions) {
                    case 2:
                        new Excp('E_EXPIRATION');
                        break;
                    case 3:
                        new Excp('E_INSTALLING');
                        break;
                }
            }
            
            if($val && $connect) {
                $ssh = new SecureShell($val->ssh_ip, $val->ssh_port, $val->ssh_login, $val->ssh_password);
            } elseif($val) {
                $ssh = TRUE;
            } else {
                $ssh = FALSE;
                new Excp('E_SERVER_NO_EXISTS');
            }
            return $ssh;
        }
        
        
        protected function forNotLogged($url = 'User:Main') {
            if(UID) {
                Router::redirect($url);
            }
        }
        
        
        protected function forLogged($url = 'Login') {
            if(!UID) {
                Router::redirect($url);
            }
        }
        
        
        protected function isCorrect($type) {
            $i = $this->db()
                    ->tables('servers, server_types')
                    ->select('servers.id')
                    ->where('servers.id', SID)
                    ->where('servers.type', 'server_types.id', TRUE)
                    ->where('server_types.name', $type)
                    ->fetch();
            
            if(!$i) {
                new \inc\Dialog('E_WRONG_PAGE');
            }
        }
    }