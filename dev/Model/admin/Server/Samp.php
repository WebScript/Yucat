<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin\Server
     * @name       Menu
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.6
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin\Server;
    
    class Samp extends \Model\BaseModel {
        public function control(\inc\Servers\SecureShell $ssh, $port, $action) {
            $name = 'samp' . $port;
            
            switch($action) {
                case 'start' :
                    if(!$ssh->exec('pidof ' . $name)) {
                        if($ssh->exec('cd ' . SRV_DIR . 'SAMP/' . $port . '/; ./' . $name . ' &')) return 1;
                        else return 0;
                    } else return 2;
                    break;
                case 'stop' :
                    if($ssh->exec('pidof ' . $name)) {
                        if($ssh->exec('pkill ' . $name)) return 1;
                        else return 0;
                    } else return 2;
                    break;
                case 'restart' :
                    if($ssh->exec('pidof ' . $name)) {
                        $ssh->exec('pkill ' . $name);
                    }
                    
                    if($ssh->exec('cd ' . SRV_DIR . 'SAMP/' . $port . '/; ./' . $name . ' &')) return 1;
                    else return 0;
                    break;
            }
        }
        
    }