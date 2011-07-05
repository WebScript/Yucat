<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    class SAMP {
        private static $__instance = FALSE;

        public static function init() {
            if(self::$__instance == NULL) {
                self::$__instance = new SAMP();
            }
            return self::$__instance;
        }

        public function filesExists() {
            $ssh = SSH::init();

            if(!is_dir($ssh->path.'gamemodes/') ||
               !db::init()->uQuery('VIEW', 'users', UID)->rank &&
               !file_exists($ssh->path.'filterscripts/reklama.amx') ||
               !is_dir($ssh->path.'filterscripts/') ||
               !is_dir($ssh->path.'npcmodes/') ||
               !is_dir($ssh->path.'npcmodes/recordings/') ||
               !file_exists($ssh->path.'samp03svr'.$ssh->server->port) ||
               !file_exists($ssh->path.'announce') ||
               !file_exists($ssh->path.'samp-npc') ||
               !file_exists($ssh->path.'server_log.txt') ||
               !file_exists($ssh->path.'server.cfg')) Message(ERR_NO_EXISTS_FILES);
        }

        public function serverControl($action = FALSE) {
            $ssh = SSH::init();
            switch($action) {
                case 'Start' :
                    if($ssh->exec('pidof samp03svr'.$ssh->server->port)) Message(ERR_IS_RUNING);
                    $ssh->exec('cd '.$ssh->dir.'; ./samp03svr'.$ssh->server->port.' > /dev/null &');

                    if($ssh->server->autorun) { 
                        $data = fopen($ssh->path.'autorun.wtf', 'w');
                        fclose($data);
                    }

                    if(!$ssh->exec('pidof samp03svr'.$ssh->server->port)) Message(ERR_FAILED_START);

                    Auth::access('SA:MP', AC_START_SVR);
                    Message(OK_START_SVR, 3);
                break;
                case 'Stop' :
                    if(!$ssh->exec('pidof samp03svr'.$ssh->server->port)) Message(ERR_NO_RUNING);
                    if(file_Exists($ssh->path.'autorun.wtf')) unlink($ssh->path.'autorun.wtf');
                    $ssh->exec('pkill samp03svr'.$ssh->server->port);

                    Auth::access('SA:MP', AC_STOP_SVR);
                    Message(OK_STOP_SVR, 3);
                break;
            }
        }

        public function write2Config($array = FALSE) {
            global $user;

            if(!$array || !is_Array($array)) Message(ERR_NO_SET_ALL);
            
            if($array['ann'] != 1 && $array['ann'] != 0 ||
               !is_numeric($array['maxpls']) ||
               !is_numeric($array['maxnpc']) ||
               !is_numeric($array['onfoot'])) Message(ERR_WRONG_SET_VALUES);

            //Control number of slots and NPC
            if($array['maxpls'] > SSH::init()->server->slots) Message(ERR_TOO_MANY_SLOTS);
            if($array['maxnpc'] > 3) Message(ERR_TO_MANY_NPC);

            $config = "echo Game Server Hosting.\n";
            $config .= "lanmode 0 \n";
            $config .= 'rcon_password '.Sec($array['r_pass'])."\n";
            $config .= 'maxplayers '.Sec($array['maxpls'])."\n";
            $config .= 'hostname '.HTMLSpecialChars($hostname)."\n";
            $config .= 'gamemode0 '.Sec($gm)."\n";
            $config .= 'filterscripts '.($user > 0 ? '' : 'reklama ').Sec($fs)."\n";
            $config .= 'announce '.Sec($ann)."\n";
            $config .= 'weburl '.Sec($weburl)."\n";
            $config .= 'maxnpc '.Sec($maxnpc)."\n";
            $config .= 'onfoot_rate '.Sec($onfoot)."\n";
            $config .= 'incar_rate '.Sec($incar_rate)."\n";
            $config .= 'weapon_rate '.Sec($w_rate)."\n";
            $config .= 'stream_distance '.Sec($str_dis)."\n";
            $config .= "stream_rate ".Sec($str_rate)."\n";
            if($_POST['mapname']) $config .= 'mapname '.Sec($map)."\n";
            if($_POST['password']) $config .= 'password '.Sec($pass)."\n";
            $config .= 'port '.SSH::init()->server->port."\n";

            $open = fopen(SSH::init()->path.'server.cfg', 'w');
            fwrite($open, $config);
            fclose($open);

            Auth::access('SA:MP', AC_EDIT_CONFIG);
            Message(OK_EDIT_CONFIG, 3);
        }

        
        public function fileControl() {
            $ssh = SSH::init();
            $dir3 = 'ssh2.sftp://'.$ssh->sftp.'/opt/SAMP/copy/';

            //if file doesn't exists, fix it
            if(!is_dir($ssh->path))                        mkdir($ssh->path, 0777);
            if(!file_exists($ssh->path.'server.cfg'))      copy($dir3.'server.cfg',        $ssh->path.'server.cfg');
            if(!file_exists($ssh->path.'server_log.txt'))  copy($dir3.'server_log.txt',    $ssh->path.'server_log.txt');
            if(!file_exists($ssh->path.'samp.ban'))        copy($dir3.'samp.ban',          $ssh->path.'samp.ban');
            if(!file_exists($ssh->path.'samp03svr'.$port)) copy($dir3.'samp03svr',         $ssh->path.'samp03svr'.$ssh->server->port);
            if(!file_exists($ssh->path.'announce'))        copy($dir3.'announce',          $ssh->path.'announce');
            if(!file_exists($ssh->path.'samp-npc'))        copy($dir3.'samp-npc',          $ssh->path.'samp-npc');
            if(!is_dir($ssh->path.'gamemodes'))            mkdir($ssh->path.'gamemodes', 0777);
            if(!is_dir($ssh->path.'filterscripts'))        mkdir($ssh->path.'filterscripts', 0777);
            if(!is_dir($ssh->path.'scriptfiles'))          mkdir($ssh->path.'scriptfiles', 0777);
            if(!is_dir($ssh->path.'npcmodes'))             mkdir($ssh->path.'npcmodes', 0777);
            if(!is_dir($ssh->path.'npcmodes/recordings'))  mkdir($ssh->path.'npcmodes/recordings', 0777);
            if(!file_exists($ssh->path.'filterscripts/reklama.amx')) copy($dir3.'filterscripts/reklama.amx', $ssh->path.'filterscripts/reklama.amx');

            $ssh->exec('chmod 0777 /opt/'.$ssh->server->port.'/samp03svr'.$ssh->server->port);
            $ssh->exec('chmod 0777 /opt/'.$ssh->server->port.'/announce');

            Auth::access('SA:MP', AC_FIXED_SRV);
            Message(OK_FIXED_SRV, 3);
        }

        
        public function changeVersion($version=FALSE) {
            $ssh = SSH::init();
            $dir3 = 'ssh2.sftp://'.$ssh->sftp.'/opt/SAMP/upgrade/';

            if(!$version) Message(ERR_NO_SET_ALL);

            if(file_exists($ssh->path.'samp03svr'.$ssh->server->port)) unlink($ssh->path.'samp03svr'.$ssh->server->port);
            if(file_exists($ssh->path.'samp-npc')) unlink($ssh->path.'samp-npc');
            if(file_exists($ssh->path.'announce')) unlink($ssh->path.'announce');

            switch($version) {
                case '03a' :
                    $ssh->exec('cp -R /opt/SAMP/copy/update/03a/samp03svr /opt/'.$ssh->server->port.'/samp03svr'.$ssh->server->port);
                    $ssh->exec('cp -R /opt/SAMP/copy/update/03a/announce /opt/'.$ssh->server->port.'/announce');
                    $ssh->exec('cp -R /opt/SAMP/copy/update/03a/samp-npc /opt/'.$ssh->server->port.'/samp-npc');

                    $ssh->exec('chmod 0777 /opt/'.$ssh->server->port.'/samp03svr'.$port);
                break;
                case '03b' :
                    $ssh->exec('cp -R /opt/SAMP/copy/update/03b/samp03svr /opt/'.$ssh->server->port.'/samp03svr'.$ssh->server->port);
                    $ssh->exec('cp -R /opt/SAMP/copy/update/03b/announce /opt/'.$ssh->server->port.'/announce');
                    $ssh->exec('cp -R /opt/SAMP/copy/update/03b/samp-npc /opt/'.$ssh->server->port.'/samp-npc');

                    $ssh->exec('chmod 0777 /opt/'.$ssh->server->port.'/samp03svr'.$port);
                break;
                case '03c' :
                    $ssh->exec('cp -R /opt/SAMP/copy/update/03c/samp03svr /opt/'.$ssh->server->port.'/samp03svr'.$ssh->server->port);
                    $ssh->exec('cp -R /opt/SAMP/copy/update/03c/announce /opt/'.$ssh->server->port.'/announce');
                    $ssh->exec('cp -R /opt/SAMP/copy/update/03c/samp-npc /opt/'.$ssh->server->port.'/samp-npc');

                    $ssh->exec('chmod 0777 /opt/'.$ssh->server->port.'/samp03svr'.$port);
                break;
                default:
                    Message(ERR_MUST_SELECT_VERSION);
                break;
            }

            Auth::access('SA:MP', AC_CHANGE_VERSION);
            Message(OK_CHANGE_VERSION, 3);
        }

        //Game log
        public function gameLog($what, $log=' ') {
            switch($what) {
                case 1 :
                    $file = fopen($ssh->path.'server_log.txt', 'w');
                    fwrite($file, $log);
                    fclose($file);

                    Auth::access('SA:MP', AC_GLOG_SAVE);
                    Message(OK_GLOG_SAVE, 3);
                break;
                case 2 :
                    $file = fopen($ssh->path.'server_log.txt', 'w');
                    fwrite($file, '');
                    fclose($file);

                    Auth::access('SA:MP', AC_GLOG_DELETE);
                    Message(OK_GLOG_DELETE, 3);
                break;
                case 3 :
                    header('location: http://'.CFG_WEB.'/Download.php?what=glog');
                    Auth::access('SA:MP', AC_GLOG_DOWNLOAD);
                    Message(OK_GLOG_DOWNLOAD, 3);
                break;
            }
        }
        
        
        public function deleteBanlist($column) {
            foreach($column AS $lnk) {
                $log = file($ssh->path.'samp.ban');
                $file = fopen($ssh->path.'samp.ban', 'w');

                $bans = false;
                foreach($log as $line) {
                    $wtf = explode(' ', $line, 2);
                    if($wtf[0] != $lnk) $bans .= $wtf[0].' '.$wtf[1];
                }
                fwrite($file, $bans);
                fclose($file);
            }
            Auth::access('SA:MP', AC_BANLIST_DELETE);
            Message(OK_BANLIST_DELETE, 3);
        }


        //Web FTP
        public function FTPUpload($what, $size, $name, $tmp_name) {
            switch($what) {
                case 'scriptfiles' :
                    $path = $_SESSION['scc'];
                    $fix = Array('zakazane', '.php', '.php5', '.phtml', '.html', '.htm', '.jpg', '.png', '.exe', '.com');
                break;
                case 'npcmodes' : $path = $_SESSION['npc'];
                default : 
                    $path = $what.'/';
                    $fix = Array('povolene', '.amx');
                break;
            }

            FTP::init()->upload($path, (20 * 1024 * 1024), $size, $name, $tmp_name, $fix);
            Auth::access('SA:MP', AC_UPLADED_FTP);
            Message(OK_UPLOADED_FTP, 3);
        }

        
        public function FTPDelete($what, $delete) {
            switch($what) {
                case 'scriptfiles' : $path = $_SESSION['scc'];
                case 'npcmodes' : $path = $_SESSION['npc'];
                default : $path = $what;
            }

            FTP::init()->deleteFile($path, $delete);
            Auth::access('SA:MP', AC_FTP_DELETE);
            Message(OK_FTP_DELETE, 3);
        }

        
        public function FTPmkDir($name) {
            FTP::init()->makeDir($_SESSION['scc'], $name);
            Auth::access('SA:MP', AC_FTP_MKDIR);
            Message(OK_FTP_MKDIR, 3);
        }

        
        public function FTPOpen($what, $dir) {
            if($what == 'scriptfiles') {
                $_SESSION['scc'] = FTP::init()->OpenDir($_SESSION['scc'], $dir, 'scriptfiles/');
                header('location: scriptfiles');
            } elseif($what == 'npcmodes') {
                $_SESSION['npc'] = FTP::init()->OpenDir($_SESSION['npc'], $dir, 'npcmodes/');
                header('location: npcmodes');
            }
        }

        
        public function FTP_SF_saveEdit($name, $text) {
            FTP::init()->editFile($_SESSION['scc'], $name, $text);
            Auth::access('SA:MP', AC_FTP_SC_EDIT);
            Message(OK_FTP_SC_EDIT, 3);
        }

        
        public function backup() {
            $ssh = SSH::init();
            if(file_exists($ssh->path.'svrs/'.$ssh->server->port.'/backup.tar')) unlink($ssh->path.'./svrs/'.$ssh->server->port.'/backup.tar');
            $ssh->exec('cd /opt/'.$ssh->server->port.'; tar -cvf backup.tar scriptfiles');
            header('Location: ../../Download.php?what=backup');
            Auth::access('SA:MP', AC_BACKUP_DOWNLOAD);
            Message(OK_BACKUP_DOWNLOADED, 3);
        }

        public function installer($what) {
            $ssh = SSH::init();
            $dir3 = 'ssh2.sftp://'.$ssh->sftp.'/opt/SAMP/copy/';

            switch($what) {
                case 'exis401' :
                    if(!file_exists($ssh->path.'scriptfiles/exis')) {
                            mkdir($ssh->path.'scriptfiles/exis');
                            chmod($ssh->path.'scriptfiles/exis', 0777);
                            copy($dir3.'scriptfiles/exis/kick.txt', $ssh->path.'scriptfiles/exis/kick.txt');
                            copy($dir3.'scriptfiles/exis/config.cfg', $ssh->path.'scriptfiles/exis/config.cfg');
                            copy($dir3.'scriptfiles/exis/cenzura.txt', $ssh->path.'scriptfiles/exis/cenzura.txt');
                            copy($dir3.'scriptfiles/exis/adminlog.log', $ssh->path.'scriptfiles/exis/adminlog.log');
                    }
                    if(!file_exists($ssh->path.'filterscripts/exis401.amx')) copy($dir3.'filterscripts/exis401.amx', $ssh->path.'filterscripts/exis401.amx');
                break;
                case 'exis404' :
                    if(!file_exists($ssh->path.'scriptfiles/eXis')) {
                            mkdir($ssh->path.'scriptfiles/eXis');
                            chmod($ssh->path.'scriptfiles/eXis', 0777);
                            copy($dir3.'scriptfiles/exis/kick.txt', $ssh->path.'scriptfiles/eXis/kick.txt');
                            copy($dir3.'scriptfiles/exis/config.cfg', $ssh->path.'scriptfiles/eXis/config.cfg');
                            copy($dir3.'scriptfiles/exis/cenzura.txt', $ssh->path.'scriptfiles/eXis/cenzura.txt');
                            copy($dir3.'scriptfiles/exis/adminlog.log', $ssh->path.'scriptfiles/eXis/adminlog.log');
                    }
                    if(!file_exists($ssh->path.'filterscripts/exis404.amx')) copy($dir3.'filterscripts/exis404.amx', $ssh->path.'filterscripts/exis404.amx');
                break;
                case 'mtv' :
                    if(!file_exists($ssh->path.'scriptfiles/maikeroo')) {
                            mkdir($ssh->path.'scriptfiles/maikeroo');
                            $s = fopen($ssh->path.'scriptfiles/maikeroo/stiznosti.txt', 'w');
                            fclose($s);
                    }
                    if(!file_exists($ssh->path.'gamemodes/mtv.amx')) copy($dir3.'gamemodes/mtv.amx', $ssh->path.'gamemodes/mtv.amx');
                break;
                case 'mtv2' :
                    if(!file_exists($ssh->path.'scriptfiles/Maikeroo')) {
                            mkdir($ssh->path.'scriptfiles/Maikeroo');
                            mkdir($ssh->path.'scriptfiles/Maikeroo/Login');
                    }
                    if(!file_exists($ssh->path.'gamemodes/mtv2.amx')) copy($dir3.'gamemodes/mtv2.amx', $ssh->path.'gamemodes/mtv2.amx');
                break;
                case 'vidlak' :
                    if(!file_exists($ssh->path.'gamemodes/vidlak.amx')) copy($dir3.'gamemodes/vidlak.amx', $ssh->path.'gamemodes/vidlak.amx');
                break;
                case 'okrsek' :
                    if(!file_exists($ssh->path.'gamemodes/okrsek.amx')) copy($dir3.'gamemodes/okrsek.amx', $ssh->path.'gamemodes/okrsek.amx');
                break;
                case 'spwl' :
                    if(!file_exists($ssh->path.'gamemodes/SPWL.amx')) copy($dir3.'gamemodes/SPWL.amx', $ssh->path.'gamemodes/SPWL.amx');
                break;
                case 'wtlk' :
                    if(!file_exists($ssh->path.'gamemodes/WTLK.amx')) copy($dir3.'gamemodes/WTLK.amx', $ssh->path.'gamemodes/WTLK.amx');
                break;
                case 'rze74' :
                    if(!file_exists($ssh->path.'gamemodes/rze74.amx')) copy($dir3.'gamemodes/rze74.amx', $ssh->path.'gamemodes/rze74.amx');
                break;
                case 'rze8' :
                    if(!file_exists($ssh->path.'gamemodes/rze8.amx')) copy($dir3.'gamemodes/rze8.amx', $ssh->path.'gamemodes/rze8.amx');
                break;
            }

            Auth::access('SA:MP', AC_INSTALLED);
            Message(SAMP_INSTALLED_SUC, 3);
        }
    }