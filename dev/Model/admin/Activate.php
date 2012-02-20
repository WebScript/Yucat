<?php
    /**
     * 
     * @category   Yucat
     * @package    Admin
     * @name       Activate
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.5
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin;
    
    class Activate extends \Model\BaseModel {
        
        public function activate($activateId) {
            if(!strlen($activateId) == 255) return 0;
            
            $act = $this->db()
                    ->tables('users')
                    ->where('activate_id', $activateId)
                    ->fetch();
            
            if($act) {
                $this->db()
                        ->tables('users')
                        ->where('activate_id', $activateId)
                        ->update(array('activateId' => '', 'permissions' => '1'));
                return 1;
            } 
            return 0;
        }
        
    }
    