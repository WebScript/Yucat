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
    if(!$_SESSION['lang']) $_SESSION['lang'] = CFG_DEF_LANG;
    /** Set style by config */
    if(!$_SESSION['style']) $_SESSION['style'] = CFG_DEF_STYLE;

    /** Define language */
    define('LANG', $_SESSION['lang']);
    /** Define style */
    define('STYLE', $_SESSION['style']);
    
    /** Call a error handler */
    inc\Diagnostics\Debug::_init();
    /** Set developer mode */
    inc\Diagnostics\Debug::setMode(inc\Diagnostics\Debug::MODE_DEV);
    
    /** Create a connection with database */
    $db = db::_init(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);
    if(UID) {
        /** @var user's informations */
        $user = $db->uQuery(db::VIEWS, db::USERS, UID);
        /** Check if user is logged */
        //inc\Auth::isLogged();
    }
    if(!is_dir(STYLE_DIR)) ExceptionHandler::Exception('ERR_SET_STYLE');

    
    use inc\Lang;
use inc\Page;

    
    
    
    $lang = $page = inc\Lang::_init();

    \inc\Router::callRoute();





    //Load global functions
    //Page::init();

    //add ERR_EMAIL_ALREADY_REGISTRED
    //AC_LOGOUT
    /* pridat do CZ""
     * "T_PF_RANK_5" => "Administr�tor",
    "T_PF_RANK_4" => "Moder�tor",
    "T_PF_RANK_3" => "V.I.P.",
    "T_PF_RANK_2" => "Premium u��vatel",
    "T_PF_RANK_1" => "Business u��vatel",
    "T_PF_RANK_0" => "Be�n� u��vatel",
     */
   
    
    
    


    
    //Ban Control
    //Ban::banControl();
    
    $page->openPage('page_header.html');

    if(Page::getAddress(0) == 'login' && !$_COOKIE['logged']) {
        if(!$_POST['username'] && !$_POST['password']) {
            $page->openPage('template/login.html');
        } else {
            Auth::login($_POST['username'], $_POST['password'], $_POST['remember']);
            Page::goToPage('user');
        }
    } elseif(Page::getAddress(0) == 'registration' && !$_COOKIE['logged']) {
        if($_POST['register']) Auth::init()->register($_POST);
        else $page->openPage('template/registration.html');
    } elseif($_COOKIE['logged']) {
        if($_SESSION['lang'] != $user[db::USERS_LANGUAGE]) $_SESSION['lang'] = $user[db::USERS_LANGUAGE];
        if($_SESSION['style'] != $user[db::USERS_STYLE]) $_SESSION['style'] = $user[db::USERS_STYLE];

        //Header for logged users
        $page->openPage('page_sub_header.html');
        Auth::lockCheck();
        
        if(Page::getAddress(0) == 'user') {
            //Menu
            $menu = Array(
                'T_MENU_MAIN' => Array(
                    'T_MENU_NEWS' => 'news',
                    'T_MENU_PROFILE' => 'profile'
                    ),
                'T_MENU_STATISTIC' => Array(
                    'bannery' => 'stat_banners',
                    'access log' => 'stat_access'
                ),
                'T_MENU_CREDIT' => Array(
                    'T_MENU_BUY_CREDIT' => 'credit_buy',
                    'T_MENU_CODE_CREDIT' => 'credit_code',
                    'T_MENU_SEND_CREDIT' => 'credit_send',
                    'T_MENU_VIEW_BNS' => 'banners_view'
                    ),
                'T_MENU_ORDER_M' => Array(
                    'T_MENU_ORDER_SERVER' => 'order_server',
                    'T_MENU_DELETE_SERVER' => 'delete_server'
                    ),
                'T_MENU_SERVERS' => 'servers'
                );
            /*
             'T_MENU_CHAT' => 'chat',
             'T_MENU_ACCESS_LOG' => 'access_log'
             */
            
            
            Page::Menu($menu, 'user/');
            
            switch(Page::getAddress(1))
            {
                case 'news' :
                    $page->openPage('template/news.html');
                break;
                case 'profile' :
                    if($_POST['save']) Head::profile($_POST);
                    elseif($_POST['change']) Head::password($_POST);
                    $page->openPage('template/profile.html');
                break;
                
                case 'stat_banners' :
                    $page->openPage('template/banners.html');
                break;
                case 'stat_access' :
                    $page->openPage('template/access.html');
                break;
                
                
                
                case 'rules' :
                    echo 'podmienky lol';
                break;
                case 'logout' :
                    Auth::logout();
                    Page::goToPage('login');
                break;
                default :
                    Page::goToPage('user/news');
                break;
            }
        } else Page::goToPage('user');

        
        //Footer for logged users
        $page->openPage('page_sub_footer.html');
    } else Page::goToPage('login');
    
   //$ssh = SSH::init($etc[1]);
 
    $page->openPage('page_footer.html');
    ob_flush();
