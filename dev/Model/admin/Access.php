<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin
     * @name       Access
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.2
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin;
    
    use inc\Db;
    
    class Access extends \Model\BaseModel {
        
        public static function add($type, $action, $id = UID) {
            Db::_init()->tables('access')
                    ->insert(array('UID' => $id, 'type' => $type, 'action' => $action, 'ip' => UIP));
        }
    }