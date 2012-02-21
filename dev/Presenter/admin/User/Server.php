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
    use inc\Dialog;
    use inc\Router;
    use inc\Servers\Status;
    
    class Server extends \Presenter\BasePresenter {
        
        
        public function view() {
            $this->template->servers = $this->db()
                    ->tables('servers, server_types, machines')
                    ->select('servers.port, server_types.name, servers.id, servers.type, machines.ssh_ip, machines.name as mname, servers.stopped, servers.autorun')
                    ->where('servers.UID', UID)
                    ->where('servers.permissions', 5, '!=')
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

            $this->template->serversCount = count($servers);
            
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
                    $set = '';
                    foreach($slots as $key => $val) {
                        $set .= '<option value="' . $key . '" selected>' . $val . '</option>';
                    }
                    Ajax::sendJSON(array( 'form' => array('slots' => array('changeValue' => $set))));
                } else exit('{}');
            } elseif($type === 'send') {
                $order = new \Model\admin\User\Server();
                
                switch($order->order($_POST['servers'], $_POST['slots'])) {
                    case 0:
                        new Dialog('Neni volne miesto! Prosim pockajte kym sa miesto uvolni!', Dialog::DIALOG_ERROR);
                        break;
                    case 1:
                        new Dialog('Uspesne ste si objednal server!', Dialog::DIALOG_SUCCESS);
                        break;
                    case 2;
                        new Dialog('Vybrany typ servera neexistuje!', Dialog::DIALOG_ERROR);
                        break;
                    case 3:
                        new Dialog('Vybrany pocet slotov nie je dostupny pre tuto variantu!', Dialog::DIALOG_ERROR);
                        break;
                    case 4:
                        new Dialog('Nemate dostatocny kredit!', Dialog::DIALOG_ERROR);
                        break;
                }
            } else {
                $this->template->form   = $form->sendForm();
            }
        }
        
        
        public function delete() {
            $delete = new \Model\admin\User\Server();
            if($delete->deleteServer()) {
                \Model\admin\Access::add(0, 'deleted server id:' . $_POST['deleteId']);
                Ajax::sendJSON(array('redirect' => Router::traceRoute('User:Server:view')));
            } else {
                new Dialog('Nepodarilo sa zmazat server!');
            }
        }
    }