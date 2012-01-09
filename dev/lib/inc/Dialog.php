<?php


    namespace inc;

    class Dialog {
        
        const DIALOG_BASE = 1;
        
        public function __construct($message, $type = self::DIALOG_BASE) {
            $type = $this->getType($type);
            if(Ajax::isAjax()) {
                echo '{"' . $type . '" : "' . Security::protect($message) . '"}';
            } else {
                //@todo dokoncit
            }
        }
        
        
        private function getType($type) {
            switch($type) {
                case self::DIALOG_BASE :
                    return 'dialogBase';
                default :
                    return 'dialogBase';
            }
        }
    }