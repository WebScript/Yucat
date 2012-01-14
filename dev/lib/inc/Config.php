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
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;
    
    use inc\Diagnostics\Excp;

    class Config {
        
        
        /**
         * Get all data from config
         * 
         * @return array Array of data
         */
        public function getConfig() {
            $out = array();
            $config = $GLOBALS['db']->tables('config')->fetchAll();
            
            foreach($config as $val) {
                $out[$val->param] = $val->value;
            }
            
            if(!$out) new Excp('E_CANNOT_LOAD_CONFIG');
            return $out;
        }
        
        
        /**
         * Get value by param from config
         * 
         * @param string $param Param
         * @return string Value 
         */
        public function getValue($param) {
            $config = $this->getConfig();
            return $config[$param];
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
                $GLOBALS['db']
                        ->tables('config')
                        ->insert(array('param' => $param, 'value' => $value));
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
            $get = $GLOBALS['db']
                    ->tables('config')
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
         * @param string $param Name of param
         * @return BOOL
         */
        public function deleteFromConfig($param) {
            if($this->inConfig($param)) {
                $GLOBALS['db']
                        ->tables('config')
                        ->where('param', $param)
                        ->delete();
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        /**
         * Change value in config by param
         * @param string $param Name of param
         * @param string $value Value
         * @return BOOL
         */
        public function changeInConfig($param, $value) {
            if($this->inConfig($param)) {
                $GLOBALS['db']
                        ->tables('config')
                        ->where('param', $param)
                        ->update(array('value' => $value));
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
         