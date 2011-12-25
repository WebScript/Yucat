<?php

    namespace Model\admin;
    
    
    class Access extends \Model\BaseModel {
        
        public static function add($type, $action, $id = UID) {
            $GLOBALS['db']
                    ->tables('access')
                    ->insert(array('UID' => $id, 'type' => $type, 'action' => $action, 'ip' => UIP));
        }
    }