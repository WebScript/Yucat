<?php if($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) exit;

    use inc\Db;
    use inc\Config;
    use inc\Security;
    use inc\Template;
    use inc\Diagnostics\Debug;
    use inc\Diagnostics\ErrorHandler;
    
    /** 
     * Define ROOT path 
     * e.g. /var/www/yucat/developer/
     */
    define('ROOT', __DIR__ . '/');
    
    /** 
     * Define PRESENTER dir
     * e.g. Presenter/
     */
    define('PRESENTER', 'Presenter/');
    
    /** 
     * Define MODEL dir
     * e.g. Model/
     */
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
     * Define server's directory at remote server
     * e.g. /srv/
     */
    define('SRV_DIR', '/srv/');
    
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
     * Define domain URI
     * e.g. http://developer.yucat.net/
     */
    define('DOMAIN_URI', (preg_match('#^http://#i', $_SERVER['SCRIPT_URI']) ? 'http://'  : 'https://') . DOMAIN . '/');
    
    /**
     * Define protocol
     * e.g. http://
     */
    define('PROTOCOL', (preg_match('#^http://#i', DOMAIN_URI) ? 'http://'  : 'https://'));

    /** 
     * Define User IP address 
     * e.g. 92.52.33.68
     */
    define('UIP', $_SERVER['REMOTE_ADDR']);    
    
    /** Load primary configuration file */
    require_once(ROOT . 'config.conf');
    /** Load _autoload for autload classes */
    require_once(ROOT . 'lib/init.php');
    /** Call init */
    init();
    /** Set developer mode */
    Debug::setMode(Debug::MODE_DEV);
    /** Create a connection with database */
    $db = new Db(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);
    /** Create configuration class */
    //new \inc\Config();
    /** Create instance of Cookie class */
    $cookie = new inc\Cookie();
    /** Call setters */
    Security::protectInput();
    
    /** 
     * Define 'ROUTE'. route is domain path
     */
    define('ROUTE', isset($_GET['route']) ? $_GET['route'] : '');
    
    /** Set time zone */
    date_default_timezone_set(Config::_init()->getValue('time_zone'));
    /** Set style by config */
    if(!$cookie->getParam('style')) $cookie->addParam($cookie->myCid, 'style', Config::_init()->getValue('default_style'));
    /** Define style */
    define('STYLE', $cookie->getParam('style'));
    /* Check style */
    if(!is_dir(STYLE_DIR . STYLE)) {
        new \inc\Diagnostics\Excp('E_MISSING_STYLE');
    }
    /** Call router */
    $router = new \inc\Router();
    /** Call a language system */
    $lang = new Template\Language();
    /** Call a template system */
    $core = new Template\Core();
    

    if(!inc\Ajax::isAjax()) {
    //    Debug::timer('true');
    }
    
    
    
    /**
     * mail neni dokonceny
     * 
     * 
     * skoncil som pri form ale zostala tam este nejaka praca :D
     * form bol prerobeny z add(id, name, type) na 
     * add(name, type)
     * 
     * Odstramene minLength() a maxLength() a miesto toho pridane length(min, max)
     * setErrorType() -> setType()
     * 
     * 
     * 
     * Pridat do Dialog a do JS dialog ako ta zelena, oranzvoa alebo cervena sprava jak je nad formami vetsinou...
     * 
     * Cannot set ilegal method
     * Cannot set char as int
     * Cannot set default value for select in class Form
     * 
     * 
     * translate v dialogu
     * 
     * 
     * 
     */