<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin\Server\SAMP
     * @name       Menu
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.5
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin\Server\SAMP;
    
    class Main extends \Model\BaseModel {
        public function control() {
            if(isset($_POST['control'])) {
                
            }
            return 0;
        }
        
        
        public function data() {
            $this->db()
                    ->tables('servers')
                    ->where('UID', UID)
                    ->where('id', SID)
                    ->update(array('permissions' => isset($_POST['stop']) ? 6 : 1));
            return isset($_POST['stop']) ? 1 : 0;
        }
        
        
        public function ftp() {
            $this->db()
                    ->tables('server_ftp')
                    ->where('SID', SID)
                    ->update(array('passwd' => $_POST['password']));
            return 1;
        }
    }