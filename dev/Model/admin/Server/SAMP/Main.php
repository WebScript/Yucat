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
        
        
        public function pause() {
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
        
        
        public function install(\inc\Servers\SecureShell $ssh) {
            ignore_user_abort(true);
            $this->db()
                    ->tables('servers')
                    ->where('UID', UID)
                    ->where('id', SID)
                    ->update(array('permissions' => '3'));
            
            $port = $this->db()
                    ->tables('servers')
                    ->select('port')
                    ->where('UID', UID)
                    ->where('id', SID)
                    ->fetch();
            
            $link = $ssh->getSftpLink() . SRV_DIR . 'SAMP/' . $port->port;
            $installDir = $ssh->getSftpLink() . SRV_DIR . 'install/SAMP/';
            
            mkdir($link, 0777);
            mkdir($link . '/gamemodes/', 0777);
            mkdir($link . '/filterscripts/', 0777);
            mkdir($link . '/scriptfiles/', 0777);
            mkdir($link . '/npcmodes/', 0777);
            mkdir($link . '/npcmodes/recordings', 0777);
            mkdir($link . '/plugins/', 0777);
            
            $ssh->exec('chmod 0777 /' . SRV_DIR . 'SAMP/' . $port->port . '/*');
            
            if(file_exists($installDir . 'announce') 
                    && file_exists($installDir . 'samp03svr') 
                    && file_exists($installDir . 'samp-npc') 
                    && file_exists($installDir . 'server.cfg')) {
                copy($installDir . 'announce', $link . '/announce');
                copy($installDir . 'samp03svr', $link . '/samp03svr' . $port->port);
                copy($installDir . 'samp-npc', $link . '/samp-npc');
                copy($installDir . 'server.cfg', $link . '/server.cfg');
                $ssh->exec('chmod 0775 /' . SRV_DIR . 'SAMP/' . $port->port . '/samp03svr' . $port->port);
            }
            
            $this->db()
                    ->tables('servers')
                    ->where('UID', UID)
                    ->where('id', SID)
                    ->update(array('permissions' => '1'));
            ignore_user_abort(false);
        }
        
        
        public function fileExists(\inc\Servers\SecureShell $ssh) {
            $port = $this->db()
                    ->tables('servers')
                    ->select('port')
                    ->where('UID', UID)
                    ->where('id', SID)
                    ->fetch();
            
            return file_exists($ssh->getSftpLink() . SRV_DIR . '/SAMP/' . $port->port . '/samp03svr' . $port->port);
        }
    }