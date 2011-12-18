<?php
    /**
     * User - Server
     *
     * @category   Yucat
     * @package    Presenter\User
     * @name       Server
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\admin\User;
    
    class Server extends \Presenter\BasePresenter {
        
        
        public function view() {
            GLOBAL $router;
            $this->template->servers = $this->db()
                    ->tables('servers, server_types, machines')
                    ->select('servers.port, server_types.name, servers.id, servers.type, machines.ssh_ip, machines.name as mname, servers.stopped, servers.autorun')
                    ->where('servers.UID', UID)
                    ->where('servers.type', 'server_types.id', TRUE)
                    ->where('servers.MID', 'machines.id', TRUE)
                    ->fetchAll();
            $this->template->checkStatus = new \inc\Servers\Status();
            $this->template->router = $router;
            
        }
        
        public function order() {
            
        }
        
    }