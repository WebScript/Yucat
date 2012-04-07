<?php

    namespace Presenter\admin\Server\SAMP;
    
    use inc\Form;
    use inc\Router;
    
    class Logs extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            $this->isCorrect('SAMP');
            Router::redirect('Server:SAMP:Logs:serverLog', TRUE);
        }
        
        
        
        public function serverLog($null, $act = FALSE) {
            $ssh = $this->callServer(SID, TRUE);
            $logs = new \Model\admin\Server\SAMP\Logs();
            
            $form = new Form();
            $form->setAction('Server:SAMP:Logs:serverLog:' . SID);
            $form->setErrorMessage('length', 'error');
            $form->setMethod('POST');
           
            $form->addElement('log', 'textarea')
                    ->setValue($logs->getLog($ssh));
            
            $form->addElement('delete', 'submit')
                    ->setValue('Zmazat');
            
            $form->addElement('edit', 'submit')
                    ->setValue('Upravit');
            
            $form->addElement('download', 'submit')
                    ->setValue('Stiahnut');
            
            if($act) new \inc\Dialog('Comming soon!');
            
            $this->template->log = $logs->getLog($ssh);
            $this->template->form = $form->sendForm();
        }
        
        
        
        public function banlist() {
            
        }
    }