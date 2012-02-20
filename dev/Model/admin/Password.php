<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin
     * @name       Password
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin;
    
    class Password extends \Model\BaseModel {
        
        public function recovery($hash) {
            $password = $this->db()
                    ->tables('lost_passwords')
                    ->where('hash', $hash)
                    ->fetch();
            
            if($password) {
                $this->db()
                        ->tables('users')
                        ->where('id', $password->UID)
                        ->update(array('passwd' => $password->passwd));
                
                $this->db()
                        ->tables('lost_passwords')
                        ->where('UID', $password->UID)
                        ->delete();
                return 1;
            }
            return 0;
        }
    }