<?php if($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) exit;

    use inc\Db;
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
    $db = new Db(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB); 
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
<?php if(isset($_GET['search'])) { ?>
<br />
<form method="GET">
    Vyhladat: <input type="text" name="search"  value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
    <input type="submit" value="Hladat" />
</form>
<?php } else { ?>
<center>
    <br />
    <br />
    <br />
    <br />
    <h1>Vyhladavac</h1>
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
<form method="GET">
    Vyhladat: <input type="text" name="search"  value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
    <input type="submit" value="Hladat" />
</form> 
</center>
<?php } ?>

    <?php
        if(isset($_GET['search'])) {
            $s = $_GET['search'];

            $res = $db->tables('websites')
                    ->numRows();
            $abc = $db->tables('websites')
                    ->where('botted', '2')
                    ->numRows();
            echo 'Indexovanych stranok: <b>' . $res . '</b>, Fail stranok: <b>' . $abc . '</b><br /><br /><br /><br />';

            $s = str_replace(' ', '%', $s);
            
            $out = $db->exec("SELECT url, title, description FROM websites WHERE LENGTH(title) > 5 AND LENGTH(description) > 10 AND LENGTH(text) > 10 AND text LIKE '%" . $s . "%' ORDER BY links DESC LIMIT 20");
            $reg = '([a-zA-Z0-9\n' . preg_quote('_-= -.,?"\'!();$%/!^&|:.*+ěščřžýáíéťďóňůúĚŠČŘŽÝÁÍÉŤĎŇÓŮÚ') . ']+)';
            
            while($o = mysql_fetch_object($out)) {   
               // preg_match_all('@<///>' . $reg . $s . $reg . '<///>@i', $o->text, $matchesarray);

                echo '<b><a href="' . $o->url . '">' . $o->title . '<a/></b><br />';
                //echo implode('...', $matchesarray[1]) . '<br />';
                echo $o->description . '<br />';
                echo '<font color="green">' . $o->url . '</font>';
                echo '<br /><br /><br />';
            }
        }
        
        
        if(isset($_GET['admin'])) {

            while(1) {
                $val = $db->tables('websites')
                    ->select('id, url, title, description, text')
                        ->where('botted', '0')
                        ->limit(1)
                        ->fetch();
                
                $title = trim($val->title);
                $description = trim($val->description);
                $text = explode('<///>', $val->text);
                $text = array_filter($text);
                $text = array_unique($text);
                 
                foreach($text AS $key => $val2) {
                    if(strlen($val2) < 5) {
                        unset($text[$key]); 
                    } else {
                        $text[$key] = trim(preg_replace("/&#?[a-z0-9]{2,8};/i","",$val2));
                    }
                }
                $text = implode('<///>', $text);

                $db->tables('websites')
                        ->where('id', $val->id)
                        ->update(array('botted' => '10', 'title' => $title, 'description' => $description, 'text' => $text));
                
                echo $val->id . ' | ' . $val->url . '<br />';
                flush();
                //exit; 468,49 MB (491 248 832)
            } 
        }
