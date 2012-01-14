<?php
    /**
     * Class for sending mails
     *
     * @category   Yucat
     * @package    Library
     * @name       Mail
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * 
     * @todo Dokoncit
     */

    namespace inc;
    
    class Mail {
        private function __costruct();
        
        /**
         * Send email
         * 
         * @param string $from sender's email address
         * @param string $to responder email address
         * @param string $subject subject of email
         * @param string $message mesage...
         */
        public static function send($from, $to, $subject, $message) {
            $headers = 'From: ' . $from . "\r\n";
            $headers .= "MIME-version: 1.0 \r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            mail($to, $subject, $message, $headers);
        }
        
    }