<?php
    /**
     * Status
     *
     * @category   Yucat
     * @package    Library\Servers
     * @name       Status
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.7
     * @link       http://www.yucat.net/documentation
     */

    namespace inc\Servers;
    
    class Status {
        
        /**
         * Check status of server
         * @param integer $type Type of server e.g. 1 = SA:MP, etc.
         * @param string $ip IP of server
         * @param integer $port Port of server
         * @param BOOL $playersInfo has return player information?
         * @return array 
         */
        public final function checkStatus($type, $ip, $port, $playersInfo = FALSE) {
            switch($type) {
                case 1 : // SA:MP
                    $i = $r = 0;
                    $out = array();
                    $eip = explode('.', $ip);
                    $packets = 'SAMP';
                    foreach($eip as $val) {$packets .= chr($val);}
                    $packets .= chr($port & 0xFF) . chr($port >> 8 & 0xFF);

                    while($i < 2) { 
                        //=========== P A C K E T S "i" ===========
                        $pack = $packets . 'i';
                        $f = fsockopen('udp://' . $ip, $port, $errno, $errstr);
                        socket_set_timeout($f, 0, 100000);
                        fwrite($f, $pack);
                        
                        if(fread($f, 11)) {
                            $out['password']    = $this->Bin2Int($f, 1);
                            $out['players']     = $this->Bin2Int($f, 2);
                            $out['maxPlayers']  = $this->Bin2Int($f, 2);
                            $out['name']        = $this->Bin2String($f, 4);
                            $out['mode']        = $this->Bin2String($f, 4);
                            $out['map']         = $this->Bin2String($f, 4);
                            $i = 3;
                            break;
                        } else {
                            $out['password'] = $out['players'] = $out['maxPlayers'] = $out['name'] = $out['mode'] = $out['map'] = 0;
                            $i++;
                        }
                        fclose($f);
                    }

                    while($i == 3 && $r < 2) {
                        //=========== P A C K E T S "r" ===========
                        $pack = $packets . 'r';
                        $f = fsockopen('udp://' . $ip, $port, $errno, $errstr);
                        socket_set_timeout($f, 0, 80000);
                        fwrite($f, $pack);

                        if(fread($f, 11)) {
                            $x              = $this->Bin2Int($f, 2);
                            $x              = $this->Bin2String($f, 1);
                            $out['gravity'] = $this->Bin2String($f, 1);
                            $x              = $this->Bin2String($f, 1);
                            $x              = $this->Bin2String($f, 1);
                            $str            = $this->Bin2String($f, 1);
                            $x              = $this->Bin2String($f, 1);
                            $out['version'] = $this->Bin2String($f, 1);
                            $x              = $this->Bin2String($f, 1);
                            $out['weather'] = $this->Bin2String($f, 1);
                            $x              = $this->Bin2String($f, 1);
                            $out['website'] = $this->Bin2String($f, 1);
                            $x              = $this->Bin2String($f, 1);
                            $out['time']    = $this->Bin2String($f, 1);
                            break;
                        } else {
                            $out['gravity'] = $out['version'] = $out['weather'] = $out['website'] = $out['time'] = 0;
                            $r++;
                        }
                        fclose($f);
                    }
                    
                    if($i == 3 && $playersInfo) {
                        //=========== P A C K E T S "d" ===========
                        $pack = $packets . 'd';
                        $f = fsockopen('udp://' . $ip, $port, $errno, $errstr);
                        socket_set_timeout($f, 0, 1000000);
                        fwrite($f, $pack);
                        fread($f, 11);

                        $len = ord(fread($f, 2));
                        for($i=0;$i<$len;$i++) {
                            $out['playersInfo'][$i]['playerId']   = $this->Bin2Int($f, 1);
                            $out['playersInfo'][$i]['Name']       = $this->Bin2String($f, 1);
                            $out['playersInfo'][$i]['score']      = $this->Bin2Int($f, 4);
                            $out['playersInfo'][$i]['ping']       = $this->Bin2Int($f, 4);
                        }
                    }
                    return $out;
                break;
            }
        }
        
        
        /**
         * Convert binary to integer
         * 
         * @param resource $f Resource of fopen()
         * @param integer $len Length
         * @return integer Returned integer
         */
        private final function Bin2Int($f, $len) {
            $int = 0;
            $bin = fread($f, $len);
            $int += isset($bin[0]) ? ord($bin[0]) : 0;
            $int += isset($bin[1]) ? ord($bin[1]) << 8 : 0;
            $int += isset($bin[2]) ? ord($bin[2]) << 16 : 0;
            $int += isset($bin[3]) ? ord($bin[3]) << 24 : 0;
            return $int;
        }
        
        
        /**
         * Convert binary to string
         * @param resource $f Resource of fopen()
         * @param integer $len Length 
         * @return string Returned string
         */
        private final function Bin2String($f, $len) {
            $rLen = ord(fread($f, $len));
            if($rLen > 0) {
                return fread($f, $rLen);
            } else {
                return '';
            }
        }
    }