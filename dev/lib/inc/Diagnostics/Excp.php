<?php
    /**
     *
     * @category   Yucat
     * @package    Library\Diagnostics
     * @name       Excp
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.8
     * @link       http://www.yucat.net/documentation
     */

    namespace inc\Diagnostics;

    class Excp {
        
        public function __construct($message, $devMsg = NULL) {
            if(Debug::getMode() == Debug::MODE_PROD) {
                $log = implode('@#$', array(time(), $message, $devMsg));
                $cache = new \inc\Cache('logs');
            
                if($cache->findInLog('Exceptions.log', $log) == FALSE) {
                    $cache->addToLog('Exceptions.log', $log);
                }
                new \inc\Dialog($message);
            } else {
                new \inc\Dialog('Exception: <br />' . $message . '<br />' . $devMsg);
            }
        }
    }