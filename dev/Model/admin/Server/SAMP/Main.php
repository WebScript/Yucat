<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin\Server\SAMP
     * @name       Menu
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin\Server\SAMP;
    
    class Main extends \Model\BaseModel {
        
        public function control(\inc\Servers\SecureShell $ssh) {
            if(isset($_POST['control'])) {
                $port = $this->db()
                        ->tables('servers')
                        ->where('id', SID)
                        ->fetch();
                
                $name = 'samp' . $port->port;
                if($port->permissions != 1) return 5;
                
                switch($_POST['control']) {
                    case 'start' :
                        if(!$ssh->exec('pidof ' . $name)) {
                            $ssh->exec('cd ' . SRV_DIR . 'SAMP/' . $port->port . '/; ./' . $name . ' &', FALSE);
                            return 1;
                        }
                        return 0;
                        break;
                    case 'stop' :
                        if($ssh->exec('pidof ' . $name)) {
                            $ssh->exec('pkill ' . $name);
                            return 2;
                        }
                        return 3;
                        break;
                    case 'restart' :
                        if($ssh->exec('pidof ' . $name)) {
                            $ssh->exec('pkill ' . $name);
                        }

                        $ssh->exec('cd ' . SRV_DIR . 'SAMP/' . $port->port . '/; ./' . $name . ' &', FALSE);
                        return 4;
                        break;
                }
            }
            return 0;
        }
        
        
        public function data() {
            $this->db()
                    ->tables('servers')
                    ->where('UID', UID)
                    ->where('id', SID)
                    ->update(array('permissions' => isset($_POST['stop']) ? 6 : 1));
            return isset($_POST['stop']) ? 1 : 0;
        }
        
        
        public function ftp() {
            $this->db()
                    ->tables('server_ftp')
                    ->where('SID', SID)
                    ->update(array('passwd' => $_POST['password']));
            return 1;
        }
    }