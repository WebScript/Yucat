<?php
    /** Define ROOT path */
    define('ROOT', __DIR__);
    /** Define User Internet Protocol address */
    define('UIP', $_SERVER['REMOTE_ADDR']);
    /** Define domain */
    define('DOMAIN', $_SERVER['HTTP_HOST']);
    /** Define cache dir */
    define('TEMP', ROOT . '/temp/');
    /** Define style dir */
    define('STYLE_DIR', '/styles/');
    /** Define language dir */
    define('LANG_DIR', '/languages/');
    /** Define User Identificator (UID) */
    define('UID',isset($_COOKIE['id']) ? $_COOKIE['id'] : NULL);
    
    /** Load primary configuration file */
    require_once(__DIR__ . '/config.conf');
    /** Load _autoload for autload classes */
    require_once (__DIR__ . '/lib/init.php');

    /** Use inc\db a db */
    use inc\db;
    /** Use Exception handler for Exception */
    use inc\Diagnostics\ExceptionHandler;
    
    /** Create a connection with database */
    $db = new db(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);
    /** Create a instance of configuration class */
    $config = new \inc\Config();
    /** Load secundary configuration from db */
    $conf = $config->getConfig();
    //d($conf);
    
    /** Set time zone */
    date_default_timezone_set($conf['time_zone']);
    /** Set style by config */
    if(empty($_SESSION['style'])) $_SESSION['style'] = $conf['default_style'];
    /** Define style */
    define('STYLE', $_SESSION['style']);
    
    /** Call a error handler */
    inc\Diagnostics\Debug::enable();
    /** Set developer mode */
    inc\Diagnostics\Debug::setMode(inc\Diagnostics\Debug::MODE_DEV);
    /** Debug function */
    test();
    
    if(!is_dir(ROOT . STYLE_DIR . STYLE)) ExceptionHandler::Exception('ERR_SET_STYLE');
    
    /** Call a template system*/
    $core = new inc\Template\Core();

    
    function d($p) {
        \inc\Diagnostics\Debug::dump($p);
    }
    
    function test() {
        
       // d(\inc\Router::getOnlyAddress());
        
       // exit;
    }