<?php
    /**
     *
     * @category   Yucat
     * @package    Includes\Diagnostics
     * @name       Excp
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license   GNU GPL License
     * @version    Release: 0.1.7
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.2.0
     * 
     * @todo exception habdler sa vola dynamicky a v constructe mu je predavana sprava!
     */

    namespace inc\Diagnostics;

    class Excp {
        
        public function __construct($message, $devMsg = NULL) {
            if(Debug::getMode() == Debug::MODE_PROD) {
                $log = implode('@#$', array(time(), $message, $devMsg));
                $cache = new \inc\Cache('logs');
            
                if($cache->findInLog('Exceptions.log', $log) === FALSE) {
                    $cache->addToLog('Exceptions.log', $log);
                }
                new \inc\Dialog($message);
            } else {
                new \inc\Dialog($message . '<br />' . $devMsg);
            }
        }
    }