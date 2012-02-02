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
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin\User;
    
    class Server extends \Model\BaseModel {

        public function deleteServer($id) {
            if(!isset($id)) return 0;
            $this->db()->tables('servers')->where('id', $_POST['deleteId'])->where('UID', UID)->delete();
            return 1;            
        }
    }        