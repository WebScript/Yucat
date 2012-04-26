<?php
    /* @todo dorobit! */
    namespace obj;
    
    class Server {
        private $id;
        private $values;
        
        
        
        public function __construct($id) {
            $this->id = $id;
            
        }
        
        
        public function __destruct() {
            
        }
        
        
        public function save() {
            \inc\Db::_init()
                    ->tables('servers')
                    ->where('id', $this->id)
                    ->update($this->values);
        }
        
        
        public function reload() {
            $result = \inc\Db::_init()
                    ->tables('users')
                    ->where('id', $this->id)
                    ->fetch();
            
            if($result) {
                $this->values = get_object_vars($result);
            } else throw new \Exception('User doesn\' exist!', 1);
        }
        
    }