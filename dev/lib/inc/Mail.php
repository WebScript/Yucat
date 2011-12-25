<?php

    namespace inc;
    
    class Mail {
        
        public static function send($from, $to, $subject, $message) {
            $headers = 'From: ' . $from . "\r\n";
            $headers .= "MIME-version: 1.0 \r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            mail($to, $subject, $message, $headers);
        }
        
    }