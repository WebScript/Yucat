<?php
    /**
     * SecureShell
     *
     * @category   Yucat
     * @package    Library\Servers
     * @name       SecureShell
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.3
     * @link       http://www.yucat.net/documentation
     * 
     * @todo add authentificate by cert.
     */

    namespace inc\Servers;
    
    use inc\Diagnostics\Excp;
    
    class SecureShell {
        /** @var resource Resource of connection */
        private $connection;
        /** @var resource Resource of sftp connection */
        private $sftp;
        /** @var string IP address of remote server */
        private $ip;
        
        /**
         * Connect to remote server
         * 
         * @param string $ip IP address of remote server
         * @param integer $port Port of remote server
         * @param string $login Login of remote server
         * @param string $password Password of remote server
         */
        public final function __construct($ip, $port, $login, $password) {
            $this->ip = $ip;
            $this->connection = ssh2_connect($ip, $port);
            if(!$this->connection) new Excp('E_ISE', 'E_CANNOT_CONNECT_TO_SERVER');
            
            if(!ssh2_auth_password($this->connection, $login, $password)) {
                new Excp('E_ISE', 'E_CANNOT_CONNECT_TO_SERVER');
            }
            
            $this->sftp = ssh2_sftp($this->connection);
            if(!$this->sftp) new Excp('E_ISE', 'E_CANNOT_CONNECT_TO_SERVER');
        }
        
        
        /**
         * Exectute command on remote server
         * 
         * @param string $command Command
         * @param BOOL $get 
         * @param string $error Returned error from remote server
         * @return string Returned message from remove server (Error is not returned here!) 
         */
        public final function exec($command, $get = TRUE, &$error = NULL) {
            $result = ssh2_exec($this->connection, $command);
            
            if($get) {
                $error = ssh2_fetch_stream($result, SSH2_STREAM_STDERR);
                if($result === FALSE) new Excp('E_ISE', 'E_CANNOT_CONNECT_TO_SERVER');
                
                stream_set_blocking($result, TRUE);
                stream_set_blocking($error, TRUE);
                $error = stream_get_contents($error);
            
                /* Log returned error */
                if($error) {
                    $log = implode('@#$', array(time(), $this->ip, $command, $error));
                    $cache = new \inc\Cache('logs');
                    if($cache->findInLog('ExecErrors.log', htmlspecialchars($log)) == FALSE) {
                        $cache->addToLog('ExecErrors.log', htmlspecialchars($log));
                    }
                }
                return stream_get_contents($result);
            }
            return 1;
        }
        
        
        /**
         * Return SFTP resource
         * 
         * @return resource Resource of SFTP
         */
        public final function getSftpLink() {
            return 'ssh2.sftp://' . $this->sftp . '/';
        }
        
    }