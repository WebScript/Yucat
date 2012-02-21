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
     * @version    Release: 0.1.0
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
                                    break 2;
                                }
                            }
                        }
                    }
                    if(!$machine) return 0; //neni volne miesto!
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
                    } else return 4;
                    
                    
                } else return 3; //zly pocet slotov
            } else return 2; //typ servera neexistuje
            return 1;
        }
    }        