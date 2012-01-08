<?php
    /**
     *
     * @category   Yucat
     * @package    Includes\Diagnostics
     * @name       ExceptionHandler
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

    class ExceptionHandler extends \Exception {
        //public static $exception = FALSE;
        
        public function __construct($message, $code = 0, \Exception $previous = NULL) {
            parent::__construct($message, $code, $previous);
            echo 'exceptionHandler was called.<br />';
        }
        
        

/*
        public static final function Error($error) {
            self::$exception = TRUE;

            $error = array(
                'type' => 'E_HANDLER', 
                'line' => '1', 
                'file' => $error, 
                'message' => $error
                );

            if(Debug::$mode == Debug::MODE_PROD) {
                ErrorHandler::addLog($error);
                if(\inc\Ajax::isAjax()) {
                    echo '{"alert" : "Error: Internal server error :("}';
                    exit;
                } else {
                    include_once(__DIR__ . '/500.html');
                }
            } elseif(Debug::$mode == Debug::MODE_DEV) {
                if(\inc\Ajax::isAjax()) {
                    echo '{"alert" : "' . $error['message'] . '"}';
                    exit;
                } else {
                    ErrorHandler::drawTable($error);
                }
            }
            exit;
        }*/
    }