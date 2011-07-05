<?php 
    /** Out buffer start */
    //ob_start(); 
    /** Session start */
    session_start();
    
    /** Load configuration file */
    require_once(dirname(__FILE__).'/../config.conf');
    /** Load _autoload for autload classes */
    require_once (dirname(__FILE__).'/../lib/init.php');

    /** Use inc\db a db */
    use inc\db;
    
    
    
    
    /** Define User IDentificator (UID) */
    //define('UID', ($_COOKIE['id'] ? $_COOKIE['id'] : NULL));
    /** Define User Internet Protocol address */
    define('UIP', $_SERVER['REMOTE_ADDR']);
    /** Define ROOT path */
    define('ROOT', dirname(__FILE__));
    /** Define cache dir */
    define('CACHE_DIR', dirname(__FILE__).'/cache/');

    /** Set language by config */
    if(!$_SESSION['lang']) $_SESSION['lang'] = CFG_DEF_LANG;
    /** Set style by config */
    if(!$_SESSION['style']) $_SESSION['style'] = CFG_DEF_STYLE;

    /** Define language */
    define('LANG', $_SESSION['lang']);
    /** Define style */
    define('STYLE', $_SESSION['style']);
    /** Define style dir */
    define('STYLE_DIR', dirname(__FILE__).'/../styles/'.STYLE.'/');
    /** Define language dir */
    define('LANG_DIR', dirname(__FILE__).'/../languages/');
    
    /** Call a error handler */
    inc\Diagnostics\Debug::_init();
    /** Set developer mode */
    //inc\Diagnostics\Debug::setMode(inc\Diagnostics\Debug::MODE_PROD);



    
    use inc\Diagnostics\Debug;
    
     
    /*
    $arr = array('one', 'two', 'three');
    $arr2 = array('one' => 'apple', 'five' => 'waterMelon', 'three' => 'lolec');
    
    
    $out = \inc\Arr::arrayKeyReplace($arr2, $arr);
    
    Debug::dump($out);*/
    


    
    //echo \inc\Router::traceRoute('class:method param1,param2,param3');
    \inc\Router::callRoute();
   
    
?>
