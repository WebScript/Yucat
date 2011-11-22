<?php

    use inc\db;
    use inc\Diagnostics\ExceptionHandler;
    
    /** Create a sesion */
    session_start();

    /** 
     * Define ROOT path 
     * e.g. /var/www/yucat/developer/
     */
    define('ROOT', __DIR__ . '/');
    
    /** 
     * Define full temporary path 
     * e.g. /var/www/yucat/developer/temp
     */
    define('TEMP', ROOT . 'temp/');
    
    /** 
     * Define full style path 
     * e.g. /var/www/yucat/developer/styles/
     */
    define('STYLE_DIR', ROOT . 'styles/');
    
    /** 
     * Define full language path 
     * e.g. /var/www/yucat/developer/languages
     */
    define('LANG_DIR', ROOT . 'languages/');
    
    /** 
     * Define domain 
     * e.g. developer.yucat.net
     */
    define('DOMAIN', $_SERVER['HTTP_HOST']);

    /** 
     * Define User IP address 
     * e.g. 92.52.33.68
     */
    define('UIP', $_SERVER['REMOTE_ADDR']);
    
    /** Load primary configuration file */
    require_once(ROOT . 'config.conf');
    /** Load _autoload for autload classes */
    require_once(ROOT . 'lib/init.php');
    
    inc\Diagnostics\Debug::enable();
    inc\Diagnostics\Debug::setMode(inc\Diagnostics\Debug::MODE_DEV);

    /** Create a connection with database */
    $db = new db(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB); 
    /** Create instance of Cookie class */
    $cookie = new inc\Cookie();
    /** Create a instance of configuration class */
    $config = new \inc\Config();
    /** Load secundary configuration from db */
    $conf = $config->getConfig();
    /** Call setters */
    setters();

    /** Set time zone */
    date_default_timezone_set($conf['time_zone']);
    /** Set style by config */
    if(!$cookie->getParam('style')) $cookie->addParam($cookie->getCid($cookie->getMyCookie()), 'style', $conf['default_style']);
    /** Define style */
    //define('STYLE', $cookie->getParam('style'));
    exit('konec');
    /** Call a error handler */
    inc\Diagnostics\Debug::enable();
    /** Set developer mode */
    inc\Diagnostics\Debug::setMode(inc\Diagnostics\Debug::MODE_DEV);
    
    if(!is_dir(STYLE_DIR . STYLE)) ExceptionHandler::Exception('ERR_SET_STYLE');
    
    /** Call a language system */
    $lang = new \inc\Template\Language(UID ? $db->tables('users')->where('id', UID)->fetch()->language : NULL);
    /** Call a template system */
    $core = new inc\Template\Core();

    
    function d($p = 'Error: Not set input!', $exit = NULL) {
        \inc\Diagnostics\Debug::dump($p);
        if($exit) {
            exit;
        }
    }

    
    function setters() {
        /** Protect all input variables */
        inc\Security::protectArray($_POST, TRUE);
        inc\Security::protectArray($_GET, TRUE);
        
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