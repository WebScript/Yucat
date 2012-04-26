<?php
    /**
     * Class for manage data from DB
     *
     * @category   Yucat
     * @package    Library
     * @name       Config
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.2
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;
    
    use inc\Diagnostics\Excp;

    final class Config {
        /** @var Config instance of this class */
        private static $singleton;
        /** var array config */
        private $config = array();
        
        
        public function __construct() {
            /* Use singleton */
            if(!self::$singleton) {            
                /* load config */
                $this->load();
             
                self::$singleton = $this;
            }
        }
        
        
        /**
         * Singleton
         * 
         * @return Config isntance
         */
        public static function _init() {
            if(!self::$singleton) return new Config();
            return self::$singleton;
        }
        
        
        /**
         * Load config to variable
         */
        private function load() {
            $config = Db::_init()->tables('config')->fetchAll();
            
            foreach($config as $val) {
                $this->config[$val->param] = $val->value;
            }
            
            if(!$this->config) new Excp('E_CANNOT_LOAD_CONFIG');
        }
        
        
        /**
         * Get all data from config
         * 
         * @return array Array of data
         */
        public function getConfig() {
            return $this->config;
        }
        
        
        /**
         * Get value by param from config
         * 
         * @param string $param Param
         * @return string Value 
         */
        public function getValue($param) {
            return isset($this->config[$param]) ? $this->config[$param] : NULL;
        }
        
        
        /**
         * Add to config param and value
         * 
         * @param string $param Name of param
         * @param string $value Value of value...
         * @return BOOL
         */
        public function addToConfig($param, $value) {
            if(!$this->inConfig($param)) {
                Db::_init()->tables('config')
                        ->insert(array('param' => $param, 'value' => $value));
                $this->load();
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        /**
         * Check if is param in config
         * 
         * @param string $param Name of param
         * @return BOOL 
         */
        public function inConfig($param) {
            $get = Db::_init()->tables('config')
                    ->select('value')
                    ->where('param', $param)
                    ->fetch();
            
            if($get->value) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        /**
         * Delete form config by param
         * 
         * @param string $param Name of param
         * @return BOOL
         */
        public function deleteFromConfig($param) {
            if($this->inConfig($param)) {
                Db::_init()->tables('config')
                        ->where('param', $param)
                        ->delete();
                $this->load();
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        /**
         * Change value in config by param
         * 
         * @param string $param Name of param
         * @param string $value Value
         * @return BOOL
         */
        public function changeInConfig($param, $value) {
            if($this->inConfig($param)) {
                Db::_init()->tables('config')
                        ->where('param', $param)
                        ->update(array('value' => $value));
                $this->load();
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
         