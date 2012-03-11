<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin\User
     * @name       Server
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.8.0
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin\User;
    
    class Server extends \Model\BaseModel {

        public function deleteServer() {
            if(isset($_POST['deleteId']) && is_numeric($_POST['deleteId'])) {
                $check = $this->db()
                        ->tables('servers')
                        ->where('id', $_POST['deleteId'])
                        ->where('UID', UID)
                        ->fetch();
                
                if($check) {
                    $this->db()
                            ->tables('servers')
                            ->where('id', $_POST['deleteId'])
                            ->where('UID', UID)
                            ->update(array('permissions' => 5));
                    
                    $this->db()
                            ->tables('server_ftp')
                            ->where('SID', $_POST['deleteId'])
                            ->update(array('passwd' => \inc\String::keyGen(8)));
                    return 1;
                }
            }
            return 0;
        }
        
        
        public function order($server, $slots) {
            $serverType = $this->db()
                    ->tables('server_types')
                    ->where('id', $server)
                    ->fetch();
            
            if($serverType) {
                $slots += $serverType->min_slots;
                if($slots >= $serverType->min_slots && $slots <= $serverType->max_slots) {
                    $machines = $this->db()
                            ->tables('machine_servers')
                            ->where('type', $server)
                            ->fetchAll();
                    
                    $machine = 0;
                    foreach($machines as $val) {
                        $port = 0;
                        $count = $this->db()
                                ->tables('servers')
                                ->where('permissions', 5, '!=')
                                ->where('MID', $val->MID)
                                ->numRows();
                        if($count < $val->count) {
                            $machine = $val->id;
                            for($i=$serverType->min_port;$i<=$serverType->max_port;$i++) {
                                $check = $this->db()
                                        ->tables('servers')
                                        ->where('MID', $machine)
                                        ->where('port', $i)
                                        ->fetch();
                                if(!$check) {
                                    $port = $i;
                                    break;
                                }
                            }
                        }
                        if($port) break;
                    }
                    if(!$machine) return 0;
                    $credit = $this->db()->tables('users')->where('id', UID)->fetch()->credit;
                    if($credit >= $slots * $serverType->cost) {
                        $this->db()
                                ->tables('users')
                                ->where('id', UID)
                                ->update(array('credit' => $credit - $slots * $serverType->cost));
                        
                        $this->db()
                            ->tables('servers')
                            ->insert(array(
                                'UID' => UID,
                                'type' => $server,
                                'MID' => $machine,
                                'port' => $port,
                                'slots' => $slots,
                                'permissions' => 1,
                                'autorun' => 1
                            ));
                        
                        $sid = $this->db()
                                ->tables('servers')
                                ->where('MID', $machine)
                                ->where('port', $port)
                                ->fetch();
                        
                        $this->db()
                                ->tables('server_ftp')
                                ->insert(array(
                                    'user' => 'ftpuser' . $port,
                                    'passwd' => \inc\String::keyGen(8),
                                    'SID' => $sid->id,
                                    'MID' => $machine,
                                    'ftp_uid' => 6000,
                                    'ftp_gid' => 6000,
                                    'dir' => SRV_DIR . $serverType->name . '/' . $port
                                ));
                    } else return 4;
                    
                    
                } else return 3;
            } else return 2;
            return 1;
        }
    }        