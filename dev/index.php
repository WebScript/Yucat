<?php

    use inc\db;
    use inc\Security;
    use inc\Template;
    use inc\Diagnostics\Debug;
    
    /** Create a sesion */
    //session_start();

    /** 
     * Define ROOT path 
     * e.g. /var/www/yucat/developer/
     */
    define('ROOT', __DIR__ . '/');
    
    define('PRESENTER', 'Presenter/');
    define('MODEL', 'Model/');
    
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

    /** Call a error handler */
    Debug::enable();
    /** Set developer mode */
    Debug::setMode(Debug::MODE_DEV);
    
    /** Create a connection with database */
    $db = new db(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB); 
    /** Create a instance of configuration class */
    $config = new \inc\Config();
    /** Load secundary configuration from db */
    $conf = $config->getConfig();
    /** Create instance of Cookie class */
    $cookie = new inc\Cookie();
    /** Call setters */
    Security::protectInput();
    
    /** 
     * Define 'ROUTE'. route is domain path
     */
    define('ROUTE', isset($_GET['route']) ? $_GET['route'] : NULL);
    
    $router = new \inc\Router();
    exit;
    

   // $cookie->addParam(2, 'name', 'test');
    
    //d(UID);
    
    
    
    
    
    
    
    
    
    
    /** Set time zone */
    date_default_timezone_set($conf['time_zone']);
    /** Set style by config */
    if(!$cookie->getParam('style')) $cookie->addParam($cookie->getCid($cookie->getMyCookie()), 'style', $conf['default_style']);
    /** Define style */
    define('STYLE', $cookie->getParam('style'));
    //Check if exists defined style
    if(!is_dir(STYLE_DIR . STYLE)) {
        inc\Diagnostics\ErrorHandler::Error('Error: can\'t find default style!');
    }
        //exit('konec');
    /** Call a language system */
    $lang = new Template\Language(UID ? $db->tables('users')->where('id', UID)->fetch()->language : NULL);
    /** Call a template system */
    $core = new Template\Core();

    
    function d($p = 'Error: Not set input!', $exit = NULL) {
        Debug::dump($p);
        if($exit) {
            exit;
        }
    }

    
    function setters() {
        /** Protect all input variables */
        Security::protectArray($_POST, TRUE);
        Security::protectArray($_GET, TRUE);
        
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