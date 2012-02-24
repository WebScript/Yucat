<?php
    /**
     *
     *
     * @category   Yucat
     * @package    Admin\Server\SAMP
     * @name       Main
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter\admin\Server\SAMP;
    
    use inc\Ajax;
    use inc\Form;
    use inc\Router;
    
    class Main extends \Presenter\BasePresenter {
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            Router::redirect('Server:SAMP:Main:profile', TRUE);
        }
        
        
        public function profile($id, $type = NULL, $act = NULL) {
            $ssh = $this->callServer($id);

            $data = $this->db()
                    ->tables('servers, server_types, machines, ftp')
                    ->select('servers.id, servers.port, servers.slots, servers.stopped, servers.autorun, server_types.name, server_types.cost, machines.name AS mname, machines.hostname, machines.ftp_port, ftp.id AS ftpid, ftp.user, ftp.passwd')
                    ->where('servers.UID', UID)
                    ->where('ftp.SID', 'servers.id', TRUE)
                    ->where('machines.id', 'servers.MID', TRUE)
                    ->fetch();
            
            $form = new Form();
            $form->setAction('Server:SAMP:Main:profile:' . SID . ':data');
            $form->setErrorMessage('length', 'error');
            $form->setMethod('POST');
           
            $form->addElement('id', 'text')
                    ->setValue($data->id);
            
            $form->addElement('name', 'text')
                    ->setValue($data->name);
            
            $form->addElement('port', 'text')
                    ->setValue($data->port);
            
            $form->addElement('slots', 'text')
                    ->setValue($data->slots);
            
            $form->addElement('stopped', 'text')
                    ->setValue($data->stopped ? : 'No');
            
            $form->addElement('autorun', 'text')
                    ->setValue($data->autorun ? 'Yes' : 'No');
            
            $form->addElement('cost', 'text')
                    ->setValue($data->cost * $data->slots * 30);
            
            $form->addElement('mname', 'text')
                    ->setValue($data->mname);
            
            $form->addElement('stop', 'checkbox')
                    ->setValue($data->stopped ? 'yes' : 'no');

            $form->addElement('save', 'submit')
                    ->setValue('Odoslat');
            
            
            $ftp = new Form();
            $ftp->setAction('Server:SAMP:Main:profile:' . SID . ':ftp');
            $ftp->setErrorMessage('length', 'error');
            $ftp->setMethod('POST');
           
            $ftp->addElement('id', 'text')
                    ->setValue($data->ftpid);
            
            $ftp->addElement('host', 'text')
                    ->setValue($data->hostname);
            
            $ftp->addElement('port', 'text')
                    ->setValue($data->ftp_port);
            
            $ftp->addElement('login', 'text')
                    ->setValue($data->user);
            
            $ftp->addElement('password', 'password')
                    ->setValue($data->passwd);

            $ftp->addElement('save', 'submit')
                    ->setValue('Odoslat');
            
            
            $control = new Form();
            $control->setAction('Server:SAMP:Main:profile:' . SID . ':control');
            $control->setErrorMessage('length', 'error');
            $control->setMethod('POST');
            
            $control->addElement('start', 'submit')
                    ->setValue('Zapnut');
            
            $control->addElement('restart', 'submit')
                    ->setValue('Force restart');
            
            $control->addElement('stop', 'submit')
                    ->setValue('Vypnut');
            
            
            if($type == 'data' && $act == 'check') {
                Ajax::sendJSON($form->validateData());
            } elseif($type == 'data' && $act == 'send') {
                Ajax::sendJSON($form->validateData());
            } elseif($type == 'control' && $act == 'check') {
                Ajax::sendJSON($control->validateData());
            } elseif($type == 'control' && $act == 'send') {
                if($control->isValidData()) {            
                    //$ssh = $this->callServer($id, TRUE);
                    //$samp = new \Model\admin\Server\Samp();
                    
                    d($_POST);
                    exit;
                    
                    //$re = $samp->control($ssh, $this->db()->tables('servers')->select('port')->where('id', SID)->fetch()->port, );
                    /*if($re) {
                        Ajax::sendJSON(array('dialogValue' => 'Server bol zapnuty'));
                    } elseif($re === 2) {
                        Ajax::sendJSON(array('dialogValue' => 'Server uz bezi'));
                    } else {
                        Ajax::sendJSON(array('dialogValue' => 'Server sa nepodarilo zapnut'));
                    }*/
                } else {
                    Ajax::sendJSON($control->validateData('Chybne vyplnene udaje!'));
                }
                
            } elseif($type == 'ftp' && $act == 'check') {
                Ajax::sendJSON($ftp->validateData());
            } elseif($type == 'ftp' && $act == 'send') {
                Ajax::sendJSON($ftp->validateData());
            } else {
                $this->template->form       = $form->sendForm();
                $this->template->control    = $control->sendForm();
                $this->template->ftp        = $ftp->sendForm();
            }
        }
    }