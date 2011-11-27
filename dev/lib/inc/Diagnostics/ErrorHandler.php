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
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license   GNU GPL License
     * @version    Release: 0.2.2
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.2.0
     */

    namespace inc\Diagnostics;

    class ErrorHandler {

        /**
         * Catch and create handler
         * @param string $mode Running mode Developer/Production
         */
        public static function createHandler($mode) { 
            if(error_get_last()) {
                if($mode == Debug::MODE_PROD) {
                    self::addLog(error_get_last());
                    if(\inc\Ajax::isAjax()) {
                        echo '{"alert" : "Error: Internal server error :("}';
                    } else {
                        include_once(dirname(__FILE__) . '/500.html');
                    }
                } elseif($mode == Debug::MODE_DEV) {
                    self::drawTable(error_get_last());
                }
            }
        }
        
        
        /**
         * Write and formate source code for Developer version of ErrorHandler
         * @param string $file link to file 
         * @param integer number of line
         */
        public static function highlightSource($file, $line) {
            if($line < 7) return FALSE;
            if(function_exists('ini_set')) {
                ini_set('highlight.html', '#06B');
                ini_set('highlight.default', '#000');
                ini_set('highlight.comment', '#998; font-style: italic');
                ini_set('highlight.string', '#080');
                ini_set('highlight.keyword', '#D24; font-weight: bold');
                }
                
            $source = highlight_string(file_get_contents($file), TRUE);
            $source = str_replace("\n", '', $source);
            $source = explode('<br />', $source);
            $source = array_slice($source, ($line - 7), 14);
            
            $i = $line - 6;
            $output = '';
            foreach($source as $value) {
               
                if($i == $line) {
                    $output .= '<span class="highlight">Line ' . $i
                             . ': ' . strip_tags($value) . "</span>";
                } else {
                    $output .= '<span class="line">Line ' . $i 
                             . ':</span>' . $value . "\n";
                }
                
                $i++;
            }
            return $output;
        }
        
        
        /**
         * Draw a HTML table with handled error 
         * @param array $error array of error getted error_get_last() function
         */
        public static function drawTable(array $error, $date = FALSE) {
            $errorTypes = array(
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
            );
            
            if(!$date) $date = Date('Y/m/d H:i:s', Time());
            
            $errorName = $errorTypes[$error['type']];
            $errorMessage = $error['message'];
            $errorLine = $error['line'];
            $errorFile = $error['file'];
            $errorParsedFile[0] = substr($errorFile, 0, strrpos($errorFile, '/') + 1);
            $errorParsedFile[1] = substr($errorFile, strrpos($errorFile, '/') + 1);
            
            if($error['type'] != E_DEPRECATED) {
                include(dirname(__FILE__) . '/BSoD.phtml');
            }
        } 
        
        
        /**
         * With this function you can add to log error messages in production mode
         * @param array $error 
         */
        public static function addLog(array $error) {
            $date = Date('U');
            $log = $date . ' @#$ ' . $error['type'] . ' @#$ ' . $error['line']
                 . ' @#$ ' . $error['file'] . ' @#$ ' . $error['message'];
            
            $cache = new \inc\Cache('logs');
            
            if($cache->findInLog('Errors.log', $log) === FALSE) {
                $cache->addToLog('Errors.log', $log);
            }
        }
        
        
        
        public static function error404() {
            if(\inc\Ajax::isAjax()) {
                echo '{"alert" : "Error: 404 Page not found!"}';
            } else {
                include_once(dirname(__FILE__) . '/404.html');
            }
            exit;
        }
        
        
        
        
        public static final function Error($error) {
            if(Debug::$mode == Debug::MODE_PROD) {
                $error = array('ErrorHandler', '0', '0', $error);
                self::addLog($error);
                if(\inc\Ajax::isAjax()) {
                    echo '{"alert" : "Error: Internal server error :("}';
                } else {
                    include_once(dirname(__FILE__) . '/500.html');
                }
                exit;
            } elseif(Debug::$mode == Debug::MODE_DEV) {
                exit($error);
            }
        }
     }