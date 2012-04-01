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
    
    use inc\Form;
    
    class Config extends \Presenter\BasePresenter {
        
        public function config() {
            $ssh = $this->callServer(SID, TRUE);
            $config = new \Model\admin\Server\SAMP\Config();
            
            $data = $config->getConfig($ssh);
            $announce = array(0, 1);
            $query = array(0, 1);
            
            $gamemodes = array('a', 'b');
            $maxplayers = array(1,2,3);
            $maxnpc = array(1,2,3);
            
            $form = new Form();
           
            $form->setAction('Server:SAMP:Config:config:' . SID . ':data');
            $form->setErrorMessage('length', 'error');
            $form->setMethod('POST');
            
            $form->addElement('rcon_password', 'text')
                    ->setLength(3, 30)
                    ->setValue($data['rcon_password']);
            
            $form->addElement('port', 'text')
                    ->setValue($data['port']);
            
            $form->addElement('maxplayers', 'select', $maxplayers)
                    ->setValue($data['maxplayers']);
            
            $form->addElement('maxnpc', 'select', $maxnpc)
                    ->setValue($data['maxnpc']);
            
            $form->addElement('hostname', 'text')
                    ->setLength(3, 30)
                    ->setValue($data['hostname']);
            
            $form->addElement('plugins', 'text')
                    ->setValue($data['plugins']);
            
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
            
            $this->template->form = $form->sendForm();
        }
        
        public function reinstall() {
            
        }
        
        public function check() {
            
        }
    }