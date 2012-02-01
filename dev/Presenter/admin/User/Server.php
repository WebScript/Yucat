<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin\User
     * @name       Server
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.5
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter\admin\User;
    
    use inc\Ajax;
    use inc\Router;
    use inc\Servers\Status;
    
    class Server extends \Presenter\BasePresenter {
        
        
        public function view() {
            $this->template->servers = $this->db()
                    ->tables('servers, server_types, machines')
                    ->select('servers.port, server_types.name, servers.id, servers.type, machines.ssh_ip, machines.name as mname, servers.stopped, servers.autorun')
                    ->where('servers.UID', UID)
                    ->where('servers.type', 'server_types.id', TRUE)
                    ->where('servers.MID', 'machines.id', TRUE)
                    ->fetchAll();
            $this->template->checkStatus = new Status();
            $this->template->router = Router::_init();
            
        }
        
        
        public function order() {
            
        }
        
        
        public function deleteSend() {
            $delete = new \Model\admin\User\Server();
            $delete->deleteServer($_POST['deleteId']);
            \Model\admin\Access::add(0, 'delete server');
            Ajax::sendJSON(array('redirect' => Router::traceRoute('User:Server:view')));
        }
        
    }