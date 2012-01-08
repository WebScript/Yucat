#!/usr/bin/php 
<?php
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
                ->order('id')
                ->limit(1)
                ->fetch();
        
        if(!$val) {echo "Konec!!\n"; exit;}

        $content = parseContent($val->url);
        if($content === FALSE) {$botted = 2;}
         else {
            $botted = 1;
            $postfix = array(
                '.html',
                '.htm',
                '.php',
                '.phps',
                '.asp',
                '.aspx',
                '.phtml',
                '.xhtml',
                
                '.cz',
                '.sk',
                '.com',
                '.net',
                '.org',
                '.info',
                '.biz',
                '.eu'
            );
            //d($content['url']);
            $content['url'] = parseUrl($content['url'], $postfix);
            //d($content['url']);
            foreach($content['url'] AS $val2) {
                $get = $db->tables('websites')
                        ->select('id')
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
        $out['title']       = trim(isset($titleMatch[1][0]) ? $titleMatch[1][0] : '');
        $out['desc']        = trim(isset($descMatch[1][0]) ? $descMatch[1][0] : '');
        $out['keywords']    = isset($keyMatch[1][0]) ? $keyMatch[1][0] : '';
        $out['count']       = $a + $b;
        
        /** 
         * Bud odstrani / z http://www.yucat.net/ => http://www.yucat.net 
         * alebo odstrani posledny subor z URL napr http://www.yucat.net/neco/index.php => http://www.yucat.net/neco
         */

        if(substr($domain, -1) == '/') {
            $domain = $dws = $dwp = substr($domain, 0, -1);
        } else {
            $dwp = substr($domain, 0, strrpos($domain, '/'));
            $dws = substr($dwp, 0, strrpos($dwp, '/'));
        }
        
        $dom = parse_url($domain);
        $dom = $dom['scheme'] . '://' . $dom['host'] . '/';
        //$dws = substr_count($dws, $dom) ? $dws : substr($dom, 0, -1);
        //$dwp = substr_count($dwp, $dom) ? $dwp : substr($dom, 0, -1);
        

                
        foreach($url AS $key => $val) {
            if(!substr_count($val, 'http://') && !substr_count($val, 'https://')) {
                if(substr($val, 0, 2) == './') $val = $dwp . '/' . substr($val, 2);
                elseif(substr($val, 0, 1) == '/') $val = $dom . substr($val, 1);
                elseif(substr($val, 0, 3) == '../') $val = $dws . substr($val, 3);
                else $val = $dwp . '/' . $val;

                $url[$key] = $val;
            }

            if(substr_count($url[$key], '?')) {
                $url[$key] = substr($url[$key], 0, strpos($url[$key], '?'));
            }
        }
        
        $out['url'] = $url;
        unset($url);
        
        $textA = array_filter($textA);
        $textA = array_unique($textA);
                 
        foreach($textA AS $key => $val2) {
            if(strlen($val2) < 5) {
                unset($textA[$key]); 
            } else {
                $textA[$key] = trim(preg_replace("/&#?[a-z0-9]{2,8};/i", '', $val2));
            }
        }
        
        $out['text'] = implode('<///>', $textA);
        return $out;
    }
                
    
    
    function parseUrl(array $haystackArray, array $needleArray) {
        foreach($haystackArray AS $key => $val) {
            $n = 0;
            foreach($needleArray AS $not) {
                if(strtolower(substr($val, -strlen($not))) === $not) {
                    $n = 1;
                    break;
                }
            }
            
            if($n != 1) {
                unset($haystackArray[$key]);
            }
        }
        
        return $haystackArray;
    }