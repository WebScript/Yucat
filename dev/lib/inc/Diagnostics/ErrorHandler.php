<?php
    /**
     * This is extend class for Debug class, error handler. Creating and writing
     * error table for developer in developer mode.
     * 
     * HTML template for hander is from Nette Framework
     *
     * @category   Yucat
     * @package    Includes\Diagnostics
     * @name       ErrorHandler
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.5.1
     * @link       http://www.yucat.net/documentation
     */

    namespace inc\Diagnostics;

    class ErrorHandler {
        
        /**
         * Parse and write error
         * 
         * @param integer $code
         * @param string $message
         * @param string $file
         * @param integer $line
         * @param type $context 
         */
        public function __construct($code, $message, $file, $line, $context = NULL) {           
            if(Debug::getMode() == Debug::MODE_DEV) {
                if(\inc\Ajax::isAjax())  new \inc\Dialog('Error: ' . $message);
                else $this->drawTable($code, $message, $file, $line);
            } else {
                $log = implode('@#$', array(time(), $code, $line, $file, $message));
                $cache = new \inc\Cache('logs');
            
                if($cache->findInLog('Errors.log', $log) === FALSE) {
                    $cache->addToLog('Errors.log', $log);
                }
                
                if(\inc\Ajax::isAjax()) new \inc\Dialog('E_ISE');
                else include_once(__DIR__ . '/500.html');
            }
            exit;
        }
        

        /**
         * Draw Blue Screen of Death
         * 
         * @staticvar array $errorTypes types of errors
         * @param integer $code Type of error
         * @param string $errorMessage Error message
         * @param string $errorFile Error file
         * @param integer $errorLine Error line
         */
        private function drawTable($code, $errorMessage, $errorFile, $errorLine) {
            static $errorTypes = array(
                E_ERROR => 'Fatal Error',
                E_USER_ERROR => 'User Error',
                E_RECOVERABLE_ERROR => 'Recoverable Error',
                E_CORE_ERROR => 'Core Error',
                E_COMPILE_ERROR => 'Compile Error',
                E_PARSE => 'Parse Error',
                E_WARNING => 'Warning',
                E_CORE_WARNING => 'Core Warning',
                E_COMPILE_WARNING => 'Compile Warning',
                E_USER_WARNING => 'User Warning',
                E_NOTICE => 'Notice',
                E_USER_NOTICE => 'User Notice',
                E_STRICT => 'Strict',
                E_DEPRECATED => 'Deprecated',
                E_USER_DEPRECATED => 'User Deprecated',
                'E_HANDLER' => 'Exception Handler'
            );
                        
            $errorName = &$errorTypes[$code];
            $errorParsedFile[0] = substr($errorFile, 0, strrpos($errorFile, '/') + 1);
            $errorParsedFile[1] = substr($errorFile, strrpos($errorFile, '/') + 1);

            if($errorName != E_DEPRECATED) {
                include(__DIR__ . '/BSoD.phtml');
            }
        } 
    }