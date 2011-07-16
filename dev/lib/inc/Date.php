<?php
    namespace inc;
    
    /**
     * Date and time for view. To database is write stamp time()
     *
     * @category   Yucat
     * @package    Includes
     * @name       Date
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    class Date {
        public static $date_format = 'd. M. Y';
        public static $time_format = 'H:i:s';
        
        private function __construct() {}
        
        
        /**
         * Transfer date in seconds to date 
         * @param integer $date date in seconds
         * @return string date
         */
        public static function getDate($date) {
            if(!is_numeric($date)) return FALSE;
            return Date(self::$date_format, $date);
        }
        
        
        /**
         * Transfer date in seconds to time 
         * @param integer $date date in seconds
         * @return string date
         */
        public static function getTime($time) {
            if(!is_numeric($time)) return FALSE;
            return Date(self::$time_format, $time);
        }
    }