<?php 
    /** Out buffer start */
    ob_start(); 
    /** Session start */
    session_start();
    
    /** Load configuration file */
    require_once(dirname(__FILE__).'/config.conf');
    /** Load _autoload for autload classes */
    require_once (dirname(__FILE__).'/lib/init.php');

    /** Use inc\db a db */
    use inc\db;
    /** Use Exception handler for Exception */
    use inc\Diagnostics\ExceptionHandler;
    
    /** Define User IDentificator (UID) */
    define('UID',isset($_COOKIE['id']) ? $_COOKIE['id'] : NULL);
    /** Define User Internet Protocol address */
    define('UIP', $_SERVER['REMOTE_ADDR']);
    /** Set time zone */
    date_default_timezone_set(CFG_TIME_ZONE);
    /** Define ROOT path */
    define('ROOT', dirname(__FILE__));
    /** Define cache dir */
    define('CACHE_DIR', ROOT.'/cache/');
    /** Define style dir */
    define('STYLE_DIR', ROOT.'/styles/');
    /** Define language dir */
    define('LANG_DIR', ROOT.'/languages/');

    /** Set language by config */
    if(empty($_SESSION['lang'])) $_SESSION['lang'] = CFG_DEF_LANG;
    /** Set style by config */
    if(empty($_SESSION['style'])) $_SESSION['style'] = CFG_DEF_STYLE;

    /** Define language */
    define('LANG', $_SESSION['lang']);
    /** Define style */
    define('STYLE', $_SESSION['style']);
    
    /** Call a error handler */
    inc\Diagnostics\Debug::_init();
    /** Set developer mode */
    inc\Diagnostics\Debug::setMode(inc\Diagnostics\Debug::MODE_DEV);
    
    /** Create a connection with database */
    $db = new db(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);
    if(UID) {
        /** @var user's informations */
        //$user = $db->uQuery(db::VIEWS, db::USERS, UID);
    }
    if(!is_dir(STYLE_DIR)) ExceptionHandler::Exception('ERR_SET_STYLE');
