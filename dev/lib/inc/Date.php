<?php
    /**
     * Date and time for view. To database is write time() stamp of full date
     *
     * @category   Yucat
     * @package    Library
     * @name       Date
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.4
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;

    class Date {
        /** @var string Default format of date */
        public static $date_format = 'd. M. Y';
        /** @var string Default format of time */
        public static $time_format = 'H:i';
        
        public function __construct() {}
        
        
        /**
         * Transfer time() stamp or full date to date only
         * 
         * @param mixed $date time() stamp or full date
         * @return string date only
         */
        public static function getDate($date) { 
            if(!is_numeric($date)) {
                $date = strtotime($date);
            }
            return date(self::$date_format, $date);
        }
        
        
        /**
         * Transfer time() stamp or full date to time only
         * 
         * @param mixed $date time() stamp or full date
         * @return string time only
         */
        public static function getTime($time) {
            if(!is_numeric($time)) {
                $time = strtotime($time);
            }
            return date(self::$time_format, $time);
        }
        
        
        /**
         * Get date from $date by $param format
         * 
         * @param mixed $date input date
         * @param string $param type of output
         * @return mixed output date
         */
        public static function getParam($date, $param) {
            if(!is_numeric($date)) {
                $date = strtotime($date);
            }
            return date($param, $date);
        }
    }