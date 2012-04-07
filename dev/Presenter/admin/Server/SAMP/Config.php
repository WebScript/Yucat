<?php
    /**
     *
     *
     * @category   Yucat
     * @package    Admin\Server\SAMP
     * @name       Config
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
    
    class Config extends \Presenter\BasePresenter {
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            $this->isCorrect('SAMP');
            Router::redirect('Server:SAMP:Config:config', TRUE);
        }
        
        public function config($null, $act = NULL) {
            $ssh = $this->callServer(SID, TRUE);
            
            $cfg = new \Model\admin\Server\SAMP\Config();
            $data = $cfg->getConfig($ssh);
            $other = $cfg->getValues();
            
            $announce = [0, 1];
            $query = [0, 1];
            
            $gamemodes = array('a', 'b');
            $maxplayers = array(1,2,3);
            $maxnpc = array(1,2,3);
            
            $form = new Form();
           
            $form->setAction('Server:SAMP:Config:config:' . SID);
            $form->setErrorMessage('length', 'error');
            $form->setMethod('POST');
            
            $form->addElement('rcon_password', 'text')
                    ->setLength(3, 30)
                    ->setValue($data['rcon_password']);
            
            $form->addElement('port', 'text')
                    ->setValue($data['port']);
            
            $form->addElement('maxplayers', 'select', $other['players'])
                    ->setValue($data['maxplayers']);
            
            $form->addElement('maxnpc', 'select', $other['npc'])
                    ->setValue($data['maxnpc']);
            
            $form->addElement('hostname', 'text')
                    ->setLength(3, 30)
                    ->setValue($data['hostname']);
            
            $form->addElement('plugins', 'text')
                    ->setValue(isset($data['plugins']) ? $data['plugins'] : "");
            
            $form->addElement('gamemode', 'select', $gamemodes)
                    ->setValue($data['gamemode']);
            
            $form->addElement('filterscripts', 'text')
                    ->setValue($data['filterscripts']);
            
            $form->addElement('announce', 'select', $announce)
                    ->setValue($data['announce']);
            
            $form->addElement('query', 'select', $query)
                    ->setValue($data['query']);
            
            $form->addElement('weburl', 'text')
                    ->setLength(3, 30)
                    ->setValue($data['weburl']);
            
            $form->addElement('save', 'submit')
                    ->setValue('Odoslat');
            
            switch($act) {
                case 'check':
                    Ajax::sendJSON($form->validateData());
                    break;
                case 'send':
                    if($form->isValidData()) {
                        (new \Model\admin\Server\SAMP\Config())->saveConfig($ssh);
                        new \inc\Dialog('konfiguracny subor uspesne ulozeny!', \inc\Dialog::DIALOG_SUCCESS);
                    } else {
                        Ajax::sendJSON($form->validateData('Chybne vyplnene udaje!'));
                    }
                    break;
                default:
                    $this->template->form = $form->sendForm();
                    break;
            }
        }
        
        public function reinstall() {
            
        }
        
        public function check() {
            
        }
    }