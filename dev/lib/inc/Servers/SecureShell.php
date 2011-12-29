<?php
    /**
     * SecureShell
     *
     * @category   Yucat
     * @package    inc\Servers
     * @name       SecureShell
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace inc\Servers;
    
    use inc\Diagnostics\ExceptionHandler;
    
    class SecureShell {
        private $connection;
        private $sftp;
        
        
        public final function __construct($ip, $port, $login, $password) {
            $this->connection = ssh2_connect($ip, $port);
            if(!$this->connection) ExceptionHandler::Error('Internal Server Error 500: Cannot connect to remote server!');
            
            if(!ssh2_auth_password($this->connection, $login, $password)) {
                ExceptionHandler::Error('Internal Server Error 500: Cannot connect to remote server 2!');
            }
            
            $this->sftp = ssh2_sftp($this->connection);
            if(!$this->sftp) ExceptionHandler::Error('Internal Server Error 500: Cannot connect to remote server 3!');
        }
        
        
        public final function exec($command, &$error = NULL) {
            $result = ssh2_exec($this->connection, $command);
            $error = ssh2_fetch_stream($result, SSH2_STREAM_STDERR);
            
            if($result === FALSE) ExceptionHandler::Error('Internal Server Error 500: Cannot send command to remote server!');
            stream_set_blocking($result, TRUE);
            stream_set_blocking($error, TRUE);
            $error = stream_get_contents($error);
            return stream_get_contents($result);
        }
        
        
        public final function getSftpLink() {
            return 'ssh2.sftp://' . $this->sftp;
        }
        
    }