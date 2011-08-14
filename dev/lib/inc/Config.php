<?php
    /**
     * config
     *
     * @category   Yucat
     * @package    Includes
     * @name       Config
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.2
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     * 
     * 
     * @todo pridat: addToConfig, inConfig, deleteFromConfig
     */

    namespace inc;

    class Config {
        
        public function getConfig() {
            global $db;
            $out = array();
            $config = $db->tables('config')->fetchAll();
            
            foreach($config as $val) {
                $out[$val->param] = $val->value;
            }
            return $out;
        }
        
        
        public function getValue($param) {
            $config = $this->getConfig();
            return $config[$param];
        }
    }
         