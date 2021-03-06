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
     * @version    Release: 0.1.0
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
            $this->isCorrect('SAMP');
            Router::redirect('Server:SAMP:Main:profile', TRUE);
        }
        
        
        public function profile($null, $type = NULL, $act = NULL) {
            $ssh = $this->callServer(SID, TRUE);
            
            $main = new \Model\admin\Server\SAMP\Main();
            if(!$main->fileExists($ssh)) {
                $main->install($ssh);
            }
            
            $data = $this->db()
                    ->tables('servers, server_types, machines, server_ftp')
                    ->select('servers.id, servers.port, servers.slots, servers.permissions, servers.autorun, server_types.name, server_types.cost, machines.name AS mname, machines.hostname, machines.ssh_ip, machines.ftp_port, server_ftp.id AS ftpid, server_ftp.user, server_ftp.passwd')
                    ->where('servers.id', SID)
                    ->where('server_ftp.SID', SID)
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
            
            $form->addElement('autorun', 'text')
                    ->setValue($data->autorun ? 'Yes' : 'No');
            
            $form->addElement('cost', 'text')
                    ->setValue($data->cost * $data->slots * 30);
            
            $form->addElement('mname', 'text')
                    ->setValue($data->mname);
            
            $form->addElement('stop', 'checkbox')
                    ->setValue($data->permissions == 6 ? 'checked' : '');

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
            
            
            switch($act) {
                case 'check':
                    switch($type) {
                        case 'data':
                            Ajax::sendJSON($form->validateData());
                            break;
                        case 'control':
                            Ajax::sendJSON($control->validateData());
                            break;
                        case 'ftp':
                            Ajax::sendJSON($ftp->validateData());
                            break;
                    }
                    break;
                case 'send':
                    switch($type) {
                        case 'data':
                            if($form->isValidData()) {
                                if((new \Model\admin\Server\SAMP\Main())->pause()) {
                                    new \inc\Dialog('Server pozastaveny!');
                                } else {
                                    new \inc\Dialog('Server spusteny!');
                                }
                            } else {
                                Ajax::sendJSON($form->validateData('Chybne vyplnene udaje!'));
                            }
                            break;
                        case 'control':
                            switch((new \Model\admin\Server\SAMP\Main())->control($ssh)) {
                                case 0:
                                    new \inc\Dialog('Server uz bezi!');
                                    break;
                                case 1:
                                    new \inc\Dialog('Server bol zapnuty');
                                    break;
                                case 2:
                                    new \inc\Dialog('Server bol vypnuty');
                                    break;
                                case 3:
                                    new \inc\Dialog('Server sa nepodarilo vypnut!');
                                    break;
                                case 4:
                                    new \inc\Dialog('Server bol restartovany!');
                                    break;
                                case 5:
                                    new \inc\Dialog('Nemozete spravovat pozastaveny server!');
                                    break;
                            } 
                            break;
                        case 'ftp':
                            if($ftp->isValidData()) {
                                (new \Model\admin\Server\SAMP\Main())->ftp();
                                new \inc\Dialog('Heslo bolo zmenene!');
                            } else {
                                Ajax::sendJSON($ftp->validateData('Chybne vyplnene udaje!'));
                            }
                            break;
                    }
                    break;
                    default:
                        $this->template->form       = $form->sendForm();
                        $this->template->control    = $control->sendForm();
                        $this->template->ftp        = $ftp->sendForm();
                        $this->template->ip         = $data->ssh_ip;
                        $this->template->port       = $data->port;
                        break;
            }
        }
    }