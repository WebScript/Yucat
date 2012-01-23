<?php
    /**
     * User - Server
     *
     * @category   Yucat
     * @package    Presenter\User
     * @name       Server
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Model\admin\User;
    
    class Server extends \Model\BaseModel {

        public function deleteServer($id) {
            if(!isset($id)) return 0;
            $this->db()->tables('servers')->where('id', $_POST['deleteId'])->where('UID', UID)->delete();
            return 1;            
        }
    }        