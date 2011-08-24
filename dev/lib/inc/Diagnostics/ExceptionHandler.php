<?php

namespace inc\Diagnostics;

class ExceptionHandler {
    
    public static function Exception($error) {
        exit($error);
    }
}