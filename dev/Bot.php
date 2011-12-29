#!/usr/bin/php 
<?php
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
    
    
    /** Load primary configuration file */
    require_once(ROOT . 'config.conf');
    /** Load _autoload for autload classes */
    require_once(ROOT . 'lib/init.php');
    //Debug::timer();
    /** Call a error handler */
    //Debug::enable();
    /** Set developer mode */
   // Debug::setMode(Debug::MODE_DEV);

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
   // ignore_user_abort(1); 
    
    echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <script>
        pageScroll();
        function pageScroll() {
            window.scrollBy(0, 300);
            scrolldelay = setTimeout("pageScroll()", 10);
        }
        </script>';
    $i = 0;
    while(1) { 
        $i++;
        $val = $db->tables('websites')
                ->select('url')
                ->where('botted', 0)
                ->limit(1)
                ->fetch();
        
        if(!$val) {echo 'Konec!!'; exit;}

        $content = parseContent($val->url);
        if($content === FALSE) {$botted = 2;}
         else {
            $botted = 1;
            foreach($content['url'] AS $val2) {
                $get = $db->tables('websites')
                        ->where('url', $val2)
                        ->fetch();
                if(!$get) {
                    echo $i . ' | ' . $val->url . ' -> ' . $val2 . "\n";
                    flush();

                    $con = parseContent($val2);
                    if($con === FALSE) $add = array('url' => $val2, 'botted' => 2);
                    else $add = array(
                        'url' => $val2, 
                        'botted' => 0, 
                        'title' => $con['title'], 
                        'description' => $con['desc'], 
                        'links' => $con['count'], 
                        'keywords' => $con['keywords'], 
                        'text' => $con['text']
                        );
                    
                    $db->tables('websites')->insert($add);
                }
            }
        }
        $db->tables('websites')
            ->where('url', $val->url)
            ->update(array('botted' => $botted));
    }
    
    
    function parseContent($domain) {
        $content = @file_get_contents(Security::replaceWWW($domain));
        if($content === FALSE) return FALSE;
        
        $reg = '([a-zA-Z0-9' . preg_quote('_-=<> -.,?!();$%/!^&|:.*') . ']+)';
        $reg2 = '([a-zA-Z0-9' . preg_quote('_-=<> -.,?!();$%/!^&|:.*+ěščřžýáíéťďóňůúĚŠČŘŽÝÁÍÉŤĎŇÓŮÚ') . ']+)';
        $reg3 = '([a-zA-Z0-9\n' . preg_quote('_-= -.,?"\'!();$%/!^&|:.*+ěščřžýáíéťďóňůúĚŠČŘŽÝÁÍÉŤĎŇÓŮÚ') . ']+)';
        
        $a = preg_match_all('@href="' . $reg . '"@i', $content, $url);
        $b = preg_match_all("@href='" . $reg . "'@i", $content, $url2);
        preg_match_all('@<title>' . $reg2 . '</title>@i', $content, $titleMatch);
        preg_match_all('@<meta name="description" content="' . $reg2 . '"@i', $content, $descMatch);
        preg_match_all('@<meta name="keywords" content="' . $reg2 . '"@i', $content, $keyMatch);
        preg_match_all('@<' . $reg3 . '>' . $reg3 . '</' . $reg3 . '>' .'@i', $content, $textMatch);

        $textA              = isset($textMatch[2]) ? $textMatch[2] : array();
        $url                = array_merge($url[1], $url2[1]);
        $out['title']       = isset($titleMatch[1][0]) ? $titleMatch[1][0] : '';
        $out['desc']        = isset($descMatch[1][0]) ? $descMatch[1][0] : '';
        $out['keywords']    = isset($keyMatch[1][0]) ? $keyMatch[1][0] : '';
        $out['count']       = $a + $b;
        $out['text']        = '';
        
        foreach($url AS $key => $val) {
            if(!substr_count($val, 'http://') && !substr_count($val, 'https://')) {
                $url[$key] = $domain . (substr($val, 0, 1) == '/' && substr($domain, -1) == '/' ? substr($val, 1) : $val);
            }
            
            if(substr_count($url[$key], '?')) {
                $url[$key] = substr($url[$key], 0, strpos($url[$key], '?'));
            }
        }
        
        $out['url'] = $url;
        unset($url);
        foreach($textA AS $val) {$out['text'] .= $val . '<///>';}
        return $out;
    }