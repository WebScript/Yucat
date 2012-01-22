<?php
    require 'lib/init.php';
    define('ROOT', __DIR__ . '/');
    
    function index($dir2) {
        $open = opendir($dir2);
        $out = array();

        while($dir = readdir($open)) {
            if($dir == '.' || $dir == '..') continue;
            if(is_dir($dir2 . '/' . $dir)) {
                $out = array_merge($out, index($dir2 . '/' . $dir));
            } elseif(substr($dir, -4) == '.php' || substr($dir, -5) == '.html') { 
                $out[] = $dir2 . '/' . $dir;
            }
        }
        return $out;
    }
    
    $o = index(__DIR__);
    
    $num = 0;
    foreach($o as $val) {
        $num += count(file($val));
    }
    echo 'Number of lines: ' . $num . '<br />';
    
    
    if(isset($_GET['search'])) {
        $s = preg_quote($_GET['search']);
        $out = array();
        
        foreach($o as $val) {
            $file = fopen($val, 'r');
            $text = fread($file, filesize($val));
            $i = preg_match_all('#' . $s . '#i', $text, $xxx);
            if($i) {
                echo str_replace(ROOT, '', $val) . ' (' . $i . ')<br />';
            }
        }
    }