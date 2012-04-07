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
     * @version    Release: 0.1.1
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin\Server\SAMP;
    
    class Config extends \Model\BaseModel {
        
        public function getConfig(\inc\Servers\SecureShell $ssh) {
            $types = $this->db()
                    ->tables('servers')
                    ->select('port')
                    ->where('id', SID)
                    ->fetch();
            
            $vals = array();
            $file = file($ssh->getSftpLink() . 'srv/SAMP/' . $types->port . '/server.cfg');
            
            foreach($file as $val) {
                $prs = explode(' ', $val);
                $vals[trim($prs[0])] = isset($prs[1]) ? $prs[1] : ' ';
            }
            return $vals;
        }
        
        
        public function saveConfig(\inc\Servers\SecureShell $ssh) {
            $types = $this->db()
                    ->tables('servers')
                    ->select('port')
                    ->where('id', SID)
                    ->fetch();
            
            foreach($_POST as $key => $val) $_POST[$key] = trim ($val);
            
            $f = fopen($ssh->getSftpLink() . 'srv/SAMP/' . $types->port . '/server.cfg', 'w');
            
            fwrite($f, 'echo ' . \inc\Config::_init()->getValue('name') . "... \n");
            fwrite($f, "lanmode 0 \n");
            fwrite($f, "rcon_password " . $_POST['rcon_password'] . " \n");
            fwrite($f, "maxplayers " . $_POST['maxplayers'] . " \n");
            fwrite($f, "port " . $types->port . " \n");
            fwrite($f, "hostname " . $_POST['hostname'] . " \n");
            fwrite($f, "gamemode " . $_POST['gamemode'] . " \n");
            fwrite($f, "filterscripts " . $_POST['filterscripts'] . " \n");
            fwrite($f, "announce " . $_POST['announce'] . " \n");
            fwrite($f, "query " . $_POST['query'] . " \n");
            fwrite($f, "weburl " . $_POST['weburl'] . " \n");
            fwrite($f, "maxnpc " . $_POST['maxnpc'] . " \n");
            fwrite($f, "onfoot_rate 40 \n");
            fwrite($f, "incar_rate 40 \n");
            fwrite($f, "weapon_rate 40 \n");
            fwrite($f, "stream_distance 300.0 \n");
            fwrite($f, "stream_rate 1000 \n");
            fclose($f);
        }
        
        
        public function getValues() {
            $val = $this->db()
                    ->tables('servers, server_params')
                    ->select('servers.slots, server_params.value')
                    ->where('servers.id', SID)
                    ->where('server_params.SID', SID)
                    ->where('server_params.param', 'NPC')
                    ->fetch();
            
            $out = array();
            for($i=1;$i<=$val->slots;$i++) {
                $out['players'][] = $i;
            }
            for($i=1;$i<=$val->value;$i++) {
                $out['npc'][] = $i;
            }
            
            return $out;
        }
    }