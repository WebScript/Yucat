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
    use inc\Form;
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
        
        
        public function order($type = '') {
            $serverTypes = $this->db()->tables('server_types')->select('id, name')->fetchAll();
            $servers = array();
            foreach($serverTypes as $key => $val) {
                $val = get_object_vars($val);
                $servers[$val['id']] = $val['name'];
            }

            $getSrv = isset($_POST['servers']) ? $_POST['servers'] : 1;
            $srvData = $this->db()->tables('server_types')->select('min_slots, max_slots')->where('id', $getSrv)->fetch();
            $slots = array();
            if($srvData) {
                for($i = $srvData->min_slots;$i <= $srvData->max_slots;$i++) {
                    $slots[$i - $srvData->min_slots] = $i;
                }
            }

            $form = new Form();
            $form->setAction('User:Server:order');
            $form->setMethod('POST');
            $form->setErrorMessage('length', $this->template->_F_WRONG_LENGTH);
            
            $form->addElement('servers', 'select', $servers);
            $form->addElement('slots', 'select', $slots);
            $form->addElement('order', 'submit')
                    ->setValue('Objednat');            
            
            if($type === 'check') {
                
                if(isset($_POST['servers'])) {
                 //   Ajax::sendJSON(array('servers' => array('changeValue' => 'test')));
                //} else {
                    Ajax::sendJSON(array( 'form' => array('slots' => array('changeValue' => '<option value="1" selected>lolec</option><option value="2">lolec</option><option>lolec</option><option>lolec</option><option>lolec</option>'))));
                }
                //Send set value of select slots
            
            } elseif($type === 'send') {
                $slots = $_POST['slots'];
                $servers = $_POST['servers'];
                new \inc\Dialog($servers . ':' . $slots);
            } else {
                $this->template->form   = $form->sendForm();
            }
        }
        
        
        public function deleteSend() {
            $delete = new \Model\admin\User\Server();
            $delete->deleteServer($_POST['deleteId']);
            \Model\admin\Access::add(0, 'delete server');
            Ajax::sendJSON(array('redirect' => Router::traceRoute('User:Server:view')));
        }
        
    }