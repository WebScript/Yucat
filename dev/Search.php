<?php if($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) exit;

    use inc\db;
    use inc\Security;
    use inc\Diagnostics\Debug;
    use inc\Diagnostics\ErrorHandler;
    
    /** Create a sesion */
    //session_start();

    /** 
     * Define ROOT path 
     * e.g. /var/www/yucat/developer/
     */
    define('ROOT', __DIR__ . '/');
    
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
    Debug::timer();
    /** Call a error handler */
    Debug::enable();
    /** Set developer mode */
    Debug::setMode(Debug::MODE_DEV);

    /** Create a connection with database */
    $db = new db(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB); 
    /** Call setters */
    Security::protectInput();
    /** Set time zone */
    date_default_timezone_set('Europe/Bratislava');
    
    function d($p = 'Error: Not set input!', $exit = NULL) {
        Debug::dump($p);
        if($exit) {
            exit;
        }
    }
    
        echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8" />';
        
    ?>
<form method="GET">
    <input type="text" name="search"  value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
    <input type="submit" value="Hladat" />
</form>

    <?php
        if(isset($_GET['search'])) {
            $s = $_GET['search'];

            $res = $db->tables('websites')
                   // ->where('title', '')
                 //   ->where('description', '')
                    ->numRows();
            echo $res . '<br />';

            $s = str_replace(' ', '%', $s);
            
            $out = $db->exec("SELECT * FROM websites WHERE LENGTH(title) > 5 AND LENGTH(description) > 10 AND LENGTH(text) > 10 AND text LIKE '%" . $s . "%' ORDER BY COUNT(text) LIMIT 20");
            $reg = '([a-zA-Z0-9\n' . preg_quote('_-= -.,?"\'!();$%/!^&|:.*+ěščřžýáíéťďóňůúĚŠČŘŽÝÁÍÉŤĎŇÓŮÚ') . ']+)';
            
            while($o = mysql_fetch_object($out)) {   
               // preg_match_all('@<///>' . $reg . $s . $reg . '<///>@i', $o->text, $matchesarray);

                echo '<b><a href="' . $o->url . '">' . $o->title . '<a/></b><br />';
                //echo implode('...', $matchesarray[1]) . '<br />';
                echo '<font color="green">' . $o->url . '</font>';
                echo '<br /><br /><br />';
            }
        }
