<?php
    /* @todo dorobit! */
    namespace obj;
    
    class Server {
        private $id;
        private $values;
        
        
        public function __construct($id) {
            $this->id = $id;
            $this->reload();
            
        }
        
        
        public function __destruct() {
            $this->save();
        }
        
        
        public static function isUsersServer($id, $uid) {
            $result = \inc\Db::_init()
                    ->tables('servers')
                    ->select('id')
                    ->where('UID', $uid)
                    ->where('id', $id)
                    ->fetch();
            return $result ? TRUE : FALSE;
        }
        
        
        public function save() {
            \inc\Db::_init()
                    ->tables('servers')
                    ->where('id', $this->id)
                    ->update($this->values);
        }
        
        
        public function reload() {
            $result = \inc\Db::_init()
                    ->tables('servers')
                    ->where('id', $this->id)
                    ->fetch();
            
            if($result) {
                $this->values = get_object_vars($result);
            } else throw new \Exception('User doesn\' exist!', 1);
        }
        
        /** Getters */
        
        
        
    }