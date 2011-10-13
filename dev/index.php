<?php
    /** Create a sesion */
    session_start();
    
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
    define('UID', isset($_COOKIE['id']) ? $_COOKIE['id'] : NULL);
    
    /** Load primary configuration file */
    require_once(__DIR__ . '/config.conf');
    /** Load _autoload for autload classes */
    require_once(__DIR__ . '/lib/init.php');
    /** Call setters */
    setters();
    
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
    
    /** Call a language system */
    $lang = new \inc\Template\Language(isset($_COOKIE['id']) ? $db->tables('users')->where('id', UID)->fetch()->language : NULL);
    /** Call a template system */
    $core = new inc\Template\Core();

    
    function d($p = 'Error: Not set input!', $exit = NULL) {
        \inc\Diagnostics\Debug::dump($p);
        if($exit) {
            exit;
        }
    }

    function test() {
       // d(\inc\Router::getOnlyAddress());
        //exit;
        
    }
    
    function setters() {
        /** Protect all input variables */
        foreach($_GET as $key => $val) {
            $_GET[$val] = \inc\Security::protect($val);
        }
        /** And again */
        foreach($_GET as $key => $val) {
            $_GET[$val] = \inc\Security::protect($val);
        }
        
        /** Set variables for pager */
        if(!empty($_GET['select-view']) && is_numeric($_GET['select-view'])) {
            $_GET['peerPage'] = $_GET['select-view'];
        } else {
            if(empty($_GET['peerPage']) || !is_numeric($_GET['peerPage'])) {
                $_GET['peerPage'] = 25;
            }
        }
        /** And this */
        $_GET['page'] = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;        
    }