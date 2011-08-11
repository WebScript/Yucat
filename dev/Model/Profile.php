<?php
    /**
     * Profile
     *
     * @category   Yucat
     * @package    Model
     * @name       Profile
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     6* @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Model;
    
    class Profile extends \Model\BaseModel {
        
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
    }