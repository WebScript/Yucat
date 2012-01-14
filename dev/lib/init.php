<?php
    /**
     * This class manage all classes.
     *
     * @category   Yucat
     * @package    Library
     * @name       init
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.2.1
     * @link       http://www.yucat.net/documentation
     */



    function init() {
        /** Send header with sign */
        header('X-Powered-By: Yucat');
        /** Send charset UTF-8 */
        header('Content-Type: text/html; charset=utf-8');
        /** Set error handler */
        set_error_handler("errorHandler");
        /** Set error hndler for E_ERROR, etc. */
        register_shutdown_function('shutdownHandler');
        /** Turn off display errors */
        ini_set('display_errors','Off'); 
    }
     

    function __autoload($class) {
        /** Replace \ to / and check dir */
        $class = str_replace('\\', '/', $class);

        /** Check if class is in inc  so add lib to path */
        list($check) = explode('/', $class, 2);
        if($check == 'inc') {
            $class = 'lib/' . $class;
        }
        /** Create path to class file */
        $dir = ROOT . $class . '.php';
        
        /** if class exist so include it. */
        if(file_exists($dir)) {
            require_once($dir);
        }
    }
    
    
    /**
     * Alias for ErrorHandler from Diagnostics
     * @param type $code
     * @param type $message
     * @param type $file
     * @param type $line
     * @param type $context 
     */
    function errorHandler($code, $message, $file, $line, $context) {
        new inc\Diagnostics\ErrorHandler($code, $message, $file, $line, $context);
    }
    
    
    /**
     * Shutdown Handler is specia function for except core, compile or user errors
     */
    function shutdownHandler() {
        if($e = error_get_last()) {
            if($e['type'] == E_ERROR || 
                    $e['type'] == E_CORE_ERROR || 
                    $e['type'] == E_COMPILE_ERROR || 
                    $e['type'] == E_USER_ERROR) {
                new inc\Diagnostics\ErrorHandler($e['type'], $e['message'], $e['file'], $e['line']);
            }
        }
            
    }
    
    
    /**
     * Alias for dump from Debug
     * 
     * @param string $p error message
     * @param BOOL $exit exit?
     */
    function d($p = 'Error: Not set input!', $exit = NULL) {
        \inc\Diagnostics\Debug::dump($p);
//        \inc\Diagnostics\Debug::dump(debug_backtrace());

        if($exit) {
            exit;
        }
    }
