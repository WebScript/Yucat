<?php
    /**
     * Status
     *
     * @category   Yucat
     * @package    inc\Servers
     * @name       Status
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace inc\Servers;
    
    class Status {
        
        
        public final function checkStatus($type, $ip, $port) {
            switch($type) {
                case 1 : // SA:MP
                    $i = $r = 0;
                    $out = array();
                    $packets = "SAMP" . chr(strtok($ip, ".")) 
                                . chr(strtok(".")) . chr(strtok(".")) 
                                . chr(strtok(".")) . chr($port & 0xFF) 
                                . chr($port >> 8 & 0xFF);

                    while($i < 2) {
                        //=========== P A C K E T S "i" ===========
                        $pack = $packets . "i";
                        $f = fsockopen('udp://' . $ip, $port, $errno, $errstr);
                        socket_set_timeout($f, 0, 100000);
                        fwrite($f, $pack);
                        
                        if(fread($f, 11)) {
                            $out['password']    = ord(fread($f, 1));
                            $out['players']     = ord(fread($f, 2));
                            $out['maxPlayers']  = ord(fread($f, 2));
                            $str                = ord(fread($f, 4));
                            $out['name']        = HTMLEntities(fread($f, $str));
                            $str                = ord(fread($f, 4));
                            $out['mode']        = HTMLEntities(fread($f, $str));
                            $str                = ord(fread($f, 4));
                            $out['map']         = HTMLEntities(fread($f, $str));
                            $i = 3;
                            break;
                        } else {
                            $out['password'] = $out['players'] = $out['maxPlayers'] = $out['name'] = $out['mode'] = $out['map'] = 0;
                            $i++;
                        }
                        fclose($f);
                    }

                    while($i == 3 && $r < 2) {
                        //=========== P A K E T Y "r" ===========
                        $pack = $packets . "r";
                        $f = fsockopen('udp://' . $ip, $port, $errno, $errstr);
                        socket_set_timeout($f, 0, 80000);
                        fwrite($f, $pack);

                        if(fread($f, 11)) {
                            $x              = ord(fread($f, 2));
                            $str            = ord(fread($f, 1));
                            $x              = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $out['gravity'] = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $x              = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $x              = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $x              = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $out['version'] = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $x              = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $out['weather'] = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $x              = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $out['website'] = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $x              = HTMLEntities(fread($f, $str));
                            $str            = ord(fread($f, 1));
                            $out['time']    = HTMLEntities(fread($f, $str));
                            break;
                        } else {
                            $out['gravity'] = $out['version'] = $out['weather'] = $out['website'] = $out['time'] = 0;
                            $r++;
                        }
                        fclose($f);
                    }
                    return $out;
                break;
            }
        }
    }