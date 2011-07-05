<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011
     * @link http://www.gshost.eu/
     */


    class SSH {
        private static $__instance = FALSE;
        public static $server;
        public static $ssh_id;
        public static $sftp_id;
        public static $dir;
        public static $path;


        private function __connect($server_id) {
            self::$server = $server_id;
            $machine = db::init()->q('query', db::init()->uQuery('VIEWS', 'machines, servers', Array('servers.id' => $server_id, 'servers.machine' => 'machines.id')));

            if(self::$ssh_id = @ssh2_connect($machine->ip, 22)) {
                if(ssh2_auth_password(self::$ssh_id, $machine->ssh_login, $machine->ssh_password)) {
                    return self::$ssh_id;
                } else Message(ERR_SSH_NO_CONNECTED);
            } else Message(ERR_SSH_NO_CONNECTED);

            self::selectServer();
        }
        
        
        public static function init($server_id = FALSE) {
            if(self::$__instance == NULL) {
                if($server_id) {
                    self::$__instance = new SSH($server_id);
                }
            }
            return self::$__instance;
        }


        private function selectServer() {
            if(!$id) Message(ERR_NO_SET_ALL);

            self::$server = db::init()->uQuery('VIEW', 'servers', $id);
            self::$sftp_id = ssh2_sftp(self::$ssh_id);
            self::$dir = '/opt/'.self::$server->port.'/';
            self::$path = 'ssh2.sftp://'.self::$sftp_id.self::$dir;
        }


        public function exec($cmd) {
            if(!$cmd) Message(ERR_NO_SET_ALL);
            $result = ssh2_exec(self::$ssh_id, $cmd);
            if($result === FALSE) Message(ERR_NOT_EXEC);
            else {
                stream_set_blocking($result, true);
                return stream_get_contents($result);
            }
        }
    }
