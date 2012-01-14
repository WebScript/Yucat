<?php
    /**
     * Server - SAMP - Main
     *
     * @category   Yucat
     * @package    Presenter\Server\SAMP
     * @name       Main
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\admin\Server\SAMP;
    
    use inc\Ajax;
    
    class Main extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            \inc\Router::redirect('Server:SAMP:profile', TRUE);
        }
        
        
        
        public function profile($id, $type = NULL, $act = NULL) {
            $ssh = $this->callServer($id);

            $data = $this->db()
                    ->tables('servers, server_params, server_types, machines')
                    ->select('servers.id, servers.port, servers.slots, servers.stopped, servers.autorun, server_types.name, server_types.cost, machines.name AS mname')
                    ->where('servers.UID', UID)
                    ->where('servers.id', 'server_params.SID', TRUE)
                    ->where('machines.id', 'servers.MID', TRUE)
                    ->fetchAll();
            
            $form = new \inc\Form();
            $form->setAction('Server:SAMP:Main:profile:' . SID . ':data');
            $form->setMethod('POST');
           
            $form->addElement('id', 'id', 'text')
                    ->setValue($data[0]->id);
            
            $form->addElement('name', 'name', 'text')
                    ->setValue($data[0]->name);
            
            $form->addElement('port', 'port', 'text')
                    ->setValue($data[0]->port);
            
            $form->addElement('slots', 'slots', 'text')
                    ->setValue($data[0]->slots);
            
            $form->addElement('stopped', 'stopped', 'text')
                    ->setValue($data[0]->stopped ? : 'No');
            
            $form->addElement('autorun', 'autorun', 'text')
                    ->setValue($data[0]->autorun ? 'Yes' : 'No');
            
            $form->addElement('cost', 'cost', 'text')
                    ->setValue($data[0]->cost * $data[0]->slots);
            
            $form->addElement('mname', 'mname', 'text')
                    ->setValue($data[0]->mname);
            
            $form->addElement('stop', 'stop', 'checkbox')
                    ->setValue($data[0]->stopped ? 'checked' : '')
                    ->setErrorType('CHECKBOX')
                    ->setErrorMessage('error');

            $form->addElement('save', 'save', 'submit')
                    ->setValue('Odoslat');
            
            
            $ftp = new \inc\Form();
            $ftp->setAction('Server:SAMP:Main:profile:' . SID . ':ftp');
            $ftp->setMethod('POST');
           
            $ftp->addElement('id', 'id', 'text')
                    ->setValue($data[0]->id);
            
            $ftp->addElement('host', 'host', 'text')
                    ->setValue($data[0]->name);
            
            $ftp->addElement('port', 'port', 'text')
                    ->setValue($data[0]->port);
            
            $ftp->addElement('login', 'login', 'text')
                    ->setValue($data[0]->slots);
            
            $ftp->addElement('password', 'password', 'text')
                    ->setValue($data[0]->stopped ? : 'No');

            $ftp->addElement('save', 'save', 'submit')
                    ->setValue('Odoslat');
            
            
            $control = new \inc\Form();
            $control->setAction('Server:SAMP:Main:profile:' . SID . ':control');
            $control->setMethod('POST');
            
            $control->addElement('start', 'start', 'submit')
                    ->setValue('Zapnut');
            
            $control->addElement('restart', 'restart', 'submit')
                    ->setValue('Force restart');
            
            $control->addElement('stop', 'stop', 'submit')
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