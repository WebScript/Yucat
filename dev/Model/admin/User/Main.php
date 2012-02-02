<?php
    /**
     *
     *
     * @category   Yucat
     * @package    Admin\User
     * @name       Main
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.5
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin\User;
    
    class Main extends \Model\BaseModel {
        public function getUserRank($rank, $translate) { 
            switch($rank) {
                case 0: 
                    return '<b>' . $translate->_RANK_0 . '</b>';
                    break;
                case 1: 
                    return '<b>' . $translate->_RANK_1 . '</b>';
                    break;
                case 2: 
                    return '<b>' . $translate->_RANK_2 . '</b>';
                    break;
                case 3: 
                    return '<b>' . $translate->_RANK_3 . '</b>';
                    break;
                case 4: 
                    return '<b>' . $translate->_RANK_4 . '</b>';
                    break;
                case 5: 
                    return '<b>' . $translate->_RANK_5 . '</b>';
                    break;
            }
        }
        
        
        public function getNewType($id) {
            switch($id) {
                case 0:
                    return 'msg-error';
                    break;
                case 1:
                    return 'msg-ok';
                    break;
                case 2:
                    return 'msg-info';
                    break;
                case 3:
                    return 'msg-alert';
                    break;
                case 4:
                    return 'msg-loading';
                    break;
            }
        }
        
        
        public function getCreditPeerDay($uid) {
            $peer_day = 0;
            $srvs = $this->db()->tables('servers')->where('UID', $uid)->fetchAll();
            foreach($srvs as $val) {
                switch($val->type) {
                    case 'SAMP':
                        $peer_day += $val->slots * COST_SAMP / 50 / 30;
                        break;
                }
            }
            return $peer_day;
        }
        
        
        public function saveProfile() {
            return $this->db()->tables('users')->where('id', UID)->update(array(
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'street' => $_POST['street'],
                'language' => $_POST['language'],
                'city' => $_POST['city'],
                'postcode' => $_POST['postcode'],
                'telephone' => $_POST['telephone'],
                'website' => $_POST['website']
                ));
        }
        
        
        public function changePassword() {
            if($_POST['newpass'] != $_POST['retrypass']) {
                return 2;
            } else if($this->db()->tables('users')->where('id', UID)->fetch()->passwd != $_POST['oldpass']) {
                return 3;
            } else {
                return $this->db()->tables('users')->where('id', UID)->update(array('passwd' => $_POST['newpass']));
            }
        }
    }