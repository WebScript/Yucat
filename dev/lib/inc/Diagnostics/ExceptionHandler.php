<?php

namespace inc\Diagnostics;

class ExceptionHandler {
    
    
    public static function Exception($error) {
        exit($error);
    }
    
    
    public static final function Error($error) {
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
            } else {
                include_once(__DIR__ . '/500.html');
            }
            exit;
        } elseif(Debug::$mode == Debug::MODE_DEV) {
            ErrorHandler::drawTable($error);
        }
    }
}