<?php
    namespace inc;
    
    /**
     * With this class you can get status of servers
     *
     * @category   Yucat
     * @package    Includes
     * @name       Status
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Yucat Technologies (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     * @deprecated Class deprecated in Release 0.0.0
     */

    class Status {
        
        /** You can't call this function dynamicly */
        private function __consstruct() {}
        
        
        /**
         * With this function you cat get status of server
         * @param string $type type of server
         * @param IP $ip server's IP
         * @param integer $port server's port
         * @return array array of server status 
         */
        public function getServerStatus($type, $ip, $port) {
            $out = array();
            
            switch($type) {
                case 'SAMP' :
                    $error = FALSE;
                    $i = FALSE;
                    
                    while($i < 2) {
                        //=========== P A K E T S 'i' ===========
                        $f = fsockopen('udp://' . $ip, $port, $errno, $errstr);
                        socket_set_timeout($f, 0, 80000);

                        $pakets = 'SAMP';
                        $pakets .= chr(strtok($ip, '.')).chr(strtok('.')).chr(strtok('.')).chr(strtok('.'));
                        $pakets .= chr($port & 0xFF);
                        $pakets .= chr($port >> 8 & 0xFF);
                        $pakets .= 'i';

                        fwrite($f, $pakets);
                    
                        if(fread($f, 11)) {
                            $out['password']   = ord(fread($f, 1));
                            $out['players']    = ord(fread($f, 2));
                            $out['maxPlayers'] = ord(fread($f, 2));
                            $str               = ord(fread($f, 4));
                            $out['name']       = HTMLEntities(fread($f, $str));
                            $str               = ord(fread($f, 4));
                            $out['mode']       = HTMLEntities(fread($f, $str));
                            $str               = ord(fread($f, 4));
                            $out['map']        = HTMLEntities(fread($f, $str));

                            $i = 2;
                        } else if($i < 1) $i++;
                        else {
                            return FALSE;
                            $error = 1;
                            $i++;
                        }
                        fclose($f);
                    }

                    $r = 0;
                    while($i == 2 && $r < 2 && !$error) {
                        //=========== P A K E T S 'r' ===========
                        $f = fsockopen('udp://' . $ip, $port, $errno, $errstr);
                        socket_set_timeout($f, 0, 80000);

                        $pakets = 'SAMP';
                        $pakets .= chr(strtok($ip, '.')).chr(strtok('.')).chr(strtok('.')).chr(strtok('.'));
                        $pakets .= chr($port & 0xFF);
                        $pakets .= chr($port >> 8 & 0xFF);
                        $pakets .= 'i';

                        fwrite($f, $pakets);
                    
                        if(fread($f, 11)) {
                            $nvm            = ord(fread($f, 2));

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

                            $r = 2;
                        } else if($r < 1) $r++;
                        else {
                            return FALSE;
                            $i++;
                        }
                        fclose($f);
                    }
                    return $out;
                break;
            }
        }
    }