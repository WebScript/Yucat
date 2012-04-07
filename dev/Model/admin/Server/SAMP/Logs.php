<?php

    namespace Model\admin\Server\SAMP;
    
    class Logs extends \Model\BaseModel {
        public function getLog(\inc\Servers\SecureShell $ssh) {
            $port = $this->db()
                    ->tables('servers')
                    ->where('id', SID)
                    ->fetch();
            
            $link = $ssh->getSftpLink() . SRV_DIR . 'SAMP/' . $port->port . '/server_log.txt';
            
            if(file_exists($link)) {
                $f = fopen($link, 'r');
                $content = fread($f, filesize($link));
                fclose($f);
            } else {
                $content = NULL;
            }
            
            return $content;
        }
    }