<?php
    /**
     * Debug class with extends ErrorHandler is main class for debugging 
     * and fixing scripts, classes, models, etc.
     * This class have two mods: Developer and Production.
     * In Developer mode you can see errors in +/- 5 lines of code about error
     * and have GUI for easier fixing and debugging scripts.
     * In Production mode anyone can't see errors, all errors is logging to DB
     * and if in script is error you seen public_error.html file with error
     * message (Server error 500)
     *
     * @category   Yucat
     * @package    Includes
     * @name       Debug
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Yucat Technologies (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.2.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.0
     * 
     * @todo dorobit dokumentaciu
     * @todo dorobit funkcie pre developer mod
     * @todo Opravit dump(), co ked bude napr zlozeny array alebo object, atd.
     */

    namespace inc\Diagnostics;

    class Debug extends ErrorHandler {
        private $startTime;
        private static $mode = self::MODE_PROD;
        
        const MODE_DEV = 'developmentMode';
        const MODE_PROD = 'productionMode';
        
        private function __construct() {}
        
        public static function _init() {
            error_reporting(0);
            register_shutdown_function('inc\Diagnostics\Debug::debugHandler');
        }
        

        /**
         * Get time in micro seconds
         * @return float
         */
        private static function getTime() {
            List ($usec, $sec) = explode(' ', microtime());
            return ((float)$sec + (float)$usec);
        }
        
        
        /**
         * Calculates the time at which the generated page
         * @param string $input 
         */
        public static function timer($input = FALSE) {
            if(!$input) $this->startTime = self::getTime();
            else {
                echo self::getTime() - $this->startTime;
            }
        }
        
        
        /**
         * Dump input to formated text
         * @param mixed $input
         */
        public static function dump($input) {
            $out = array();
            $out[] = '<div style="background-color: #F0F0F0; float: left;">&nbsp;';
                
            if(is_array($input)) {
                $out[] = 'Array (<br />';
                
                foreach($input as $param => $value) {
                   $value = !$value ? 'NULL' : $value;
                   $out[] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                   $out[] = (is_numeric($param) ? $param : '"'.$param.'"').' => '.(is_numeric($value) || $value == 'NULL' ? $value : '"'.$value.'"').' ('.strlen($value).')<br />';
                }
                
                $out[] = ');';
            } elseif(is_string($input)) {
                $out[] = 'STRING ';
                $out[] = '"'.$input.'"'.' ('.strlen($input).')';
            } elseif(is_float ($input)) {
                $out[] = 'FLOAT ';
                $out[] = $input.' ('.strlen($input).')';
            } elseif(is_integer($input)) {
                $out[] = 'INTEGER ';
                $out[] = $input.' ('.strlen($input).')';
            } elseif(is_bool($input)) {
                $out[] = 'BOOL ';
                $out[] = $input ? 'TRUE' : 'FALSE';
            } elseif($input === NULL) {
                $out[] = 'NULL';
            } elseif(is_resource($input)) {
                $out[] = 'RESOURCE ';
                $out[] = get_resource_type($input);
            } elseif(is_object($input)) {
                $out[] = 'OBJECT ';
                $out[] = get_object_vars($input);
            } else {
                $out[] = 'Unknown type!';
            }
            
            $out[] = '&nbsp;</div><br /><br /><br />';
            echo implode('', $out);
        }
        
        
        /**
         * Set woriking mode Devepoler/Production
         * @param string $mode 
         */
        public static function setMode($mode) {
            self::$mode = $mode;
        }

        
        /**
         * This function is called in __construct for handling errors
         */
        public static function debugHandler() {
            parent::createHandler(self::$mode);
        }
    }