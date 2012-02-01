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
                    ->tables('servers, server_params, server_types, machines')
                    ->select('servers.id, servers.port, servers.slots, servers.stopped, servers.autorun, server_types.name, server_types.cost, machines.name AS mname')
                    ->where('servers.UID', UID)
                    ->where('servers.id', 'server_params.SID', TRUE)
                    ->where('machines.id', 'servers.MID', TRUE)
                    ->fetchAll();
            
            $form = new Form();
            $form->setAction('Server:SAMP:Main:profile:' . SID . ':data');
            $form->setMethod('POST');
           
            $form->addElement('id', 'text')
                    ->setValue($data[0]->id);
            
            $form->addElement('name', 'text')
                    ->setValue($data[0]->name);
            
            $form->addElement('port', 'text')
                    ->setValue($data[0]->port);
            
            $form->addElement('slots', 'text')
                    ->setValue($data[0]->slots);
            
            $form->addElement('stopped', 'text')
                    ->setValue($data[0]->stopped ? : 'No');
            
            $form->addElement('autorun', 'text')
                    ->setValue($data[0]->autorun ? 'Yes' : 'No');
            
            $form->addElement('cost', 'text')
                    ->setValue($data[0]->cost * $data[0]->slots);
            
            $form->addElement('mname', 'text')
                    ->setValue($data[0]->mname);
            
            $form->addElement('stop', 'checkbox')
                    ->setValue($data[0]->stopped ? 'checked' : '')
                    ->setType('CHECKBOX')
                    ->setErrorMessage('error');

            $form->addElement('save', 'submit')
                    ->setValue('Odoslat');
            
            
            $ftp = new Form();
            $ftp->setAction('Server:SAMP:Main:profile:' . SID . ':ftp');
            $ftp->setMethod('POST');
           
            $ftp->addElement('id', 'text')
                    ->setValue($data[0]->id);
            
            $ftp->addElement('host', 'text')
                    ->setValue($data[0]->name);
            
            $ftp->addElement('port', 'text')
                    ->setValue($data[0]->port);
            
            $ftp->addElement('login', 'text')
                    ->setValue($data[0]->slots);
            
            $ftp->addElement('password', 'text')
                    ->setValue($data[0]->stopped ? : 'No');

            $ftp->addElement('save', 'submit')
                    ->setValue('Odoslat');
            
            
            $control = new Form();
            $control->setAction('Server:SAMP:Main:profile:' . SID . ':control');
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