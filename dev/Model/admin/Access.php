<?php

    namespace Model\admin;
    
    
    class Access extends \Model\BaseModel {
        
        public static function add($type, $action, $id = UID) {
            \inc\Db::_init()->tables('access')
                    ->insert(array('UID' => $id, 'type' => $type, 'action' => $action, 'ip' => UIP));
        }
    }