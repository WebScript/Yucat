<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011
     * @link http://www.gshost.eu/
     */

    /**
     * @todo Premiestnit order a delete do classy ako orderServer a deleteServer
     * @todo Opravit deleteServer
     */

    namespace inc;

    class Server {
        /** @var Variable for singleton */
        private static $__instance = FALSE;
        /** @var Local variable of user's informations */
        private static $user;
        
        /** 
         * Const of name of servers types
         */
        const UID   = UID;
        const SAMP  = 'SAMP';
        const CS16  = 'cs16';
        const CSS   = 'css';
        const MTA   = 'mta';
        const VNT   = 'vnt';
        const TS3   = 'ts3';
        const SC    = 'SC'; //not included
        

        
        private function __construct() {
            self::$user = db::init()->uQuery(db::VIEW, db::USERS, self::UID);
        }
        
        
        /**
         * Singleton
         * @return mixes
         */
        public static function _init() {
            if(self::$__instance == NULL) {
                self::$__instance = new Server();
            }
            return self::$__instance;
        }
        
        

        public function order($type = FALSE, $slots = 4) {
            if(!$type || !is_numeric($slots)) Message(ERR_NO_SET_ALL);
            if(!self::checkSlots($type, $slots)) Message(ERR_TOO_MANY_SLOTS);
            if(!self::$user[db::USERS_RANK] && !CFG_CAN_ORD) Message(T_DONT_ORDER);
            
            $machine_id = self::init()->findVPS($type);
            if(!$machine_id) Message(ERR_IS_FULL_SERVERS);

            if(!self::init()->chceckMaxServers()) Message(ERR_HAVE_TOO_MANY_SERVERS);

            if($type == self::SAMP) {
                if(!self::init()->payServer($slots, COST_SAMP)) Message(ERR_DONT_HAVE_CREDIT);

                $port = self::init()->findFreePort($type, $machine_id);
                if(!$port) Message(ERR_IS_FULL_SERVERS);
            } else Message(ERR_CANT_ORDER);

            $autorun = $user[db::USERS_RANK] ? 1 : 1; // For commercial use set to 1 : 0

            //write data to DB
            db::init()->uQuery(db::ADD, db::SERVERS, array(
                db::SERVERS_OWNER   => self::UID,
                db::SERVERS_TYPE    => $type,
                db::SERVERS_MACHINE => $machine_id,
                db::SERVERS_PORT    => $port,
                db::SERVERS_SLOTS   => $slots,
                db::SERVERS_LOCK    => '0',
                db::SERVERS_AUTORUN => $autorun
            ));

            
            Auth::Access(Auth::HEAD, AC_ORDER_SERVER);
            Message(SERVER_ORDERED, 3);
        }

    
        public function delete($id = FALSE) {
          /*  if(!$id) Message(ERR_NO_SET_ALL);
        
            foreach($id as $list) {
                if(db::init()->q('object', db::init()->uQuery('VIEWS', 'servers', array('id' => $list, 'owner' => UID)))) {

                    $ssh = SSH::init($list);
                    if($ssh->exec('pidof samp03svr'.$srv->port)) $ssh->exec('pkill samp03svr'.$srv->port);
                    if(file_exists($ssh->path)) $ssh->exec('rm -r /opt/'.$srv->port);
                    db::init()->uQuery('DELETE', 'servers', array('id' => $list, 'owner' => UID));
                }
            }
            Auth::Access('head', AC_DELETE_SERVER);
            Message(DELETE_SERVER_OK, 3);*/
        }
        
        
        /**
         * check if entered slots is corred by server's type
         * @param string $type type of server
         * @param integer $slots number of slots
         * @return BOOL
         */
        public static function checkSlots($type, $slots) {
            $ok = FALSE;
            switch($type) {
                case self::SAMP : for($i=50;$i<=500;$i+=50) if($slots == $i) $ok = TRUE; break;
                case self::CS16 : for($i=4;$i<=32;$i+=4) if($slots == $i) $ok = TRUE; break;
                case self::CSS : for($i=4;$i<=32;$i+=4) if($slots == $i) $ok = TRUE; break;
                case self::MTA : for($i=10;$i<=100;$i+=10) if($slots == $i) $ok = TRUE; break;
                case self::VNT : for($i=50;$i<=200;$i+=50) if($slots == $i) $ok = TRUE; break;
                case self::TS3 : for($i=10;$i<=100;$i+=10) if($slots == $i) $ok = TRUE; break;
            }
            return $ok;
        }
        
        
        /**
         * Find where VPS is findly
         * @param string $type type of server whereby is search machine
         * @return integer ID of free VPS
         */
        public function findVPS($type) {
            $result = self::$user[db::USERS_RANK] <= 2 ? db::init()->uQuery(db::VIEWS, db::MACHINES, array(db::MACHINES_RANK => self::$user[db::USERS_RANK])) : db::init()->uQuery(db::VIEWS, db::MACHINES);
            
            while($lnk = db::init()->q(db::FETCH_ARRAY, $result)) {
                if(db::init()->q(db::NUM_ROWS, db::init()->uQuery(db::VIEWS, db::SERVERS, array(db::SERVERS_MACHINE => $lnk[db::MACHINES_ID], db::SERVERS_LOCK => '0'))) < $lnk[$type]) {
                    return $lnk[db::MACHINES_ID];
                }
            }
            return FALSE;
        }
        
        
        /**
         * Check how many servers have users and if he is free user and has 1 server is returned FALSE
         * @return BOOL
         */
        public function chceckMaxServers() {
            $num = db::init()->q(db::NUM_ROWS, db::init()->uQuery(db::VIEWS, db::SERVERS, array(db::SERVERS_OWNER => self::UID)));
            if(self::$user[db::USERS_RANK] == '0' && $num >= CFG_MAX_SRVS) return FALSE;
            else return TRUE;
        }
        
        
        /**
         * This function chceck and take credit for 1 day of server
         * @param integer $slots number of slots
         * @param float $server_price price of 50 slots of server
         * @return BOOL
         */
        public function payServer($slots, $server_price) {
            if(self::$user[db::USERS_CREDIT] < ($slots * $server_price / 50 / 30)) return FALSE;
            db::init()->uQuery(db::UPDATE, db::USERS, array(db::USERS_CREDIT => (self::$user[db::USERS_CREDIT] - $slots * $server_price / 50 / 30)), array(db::USERS_ID => self::UID));
            return TRUE;
        }
        
        
        /**
         * This function can find free port in DB by type of server and machine's ID
         * @param string $type type of server
         * @param integer $machine is mechine's ID
         * @return integer retrned is free port
         */
        public static function findFreePort($type, $machine) {
            switch($type) {
                case self::SAMP :
                    $min = 7777;
                    $max = 9999;
                break;
            }
            
            for($p = $min;$p < $max;$p++) {
                if(!db::init()->q(db::NUM_ROWS, db::init()->uQuery(db::VIEWS, db::SERVERS, array(db::SERVERS_PORT => $p, db::SERVERS_MACHINE => $machine)))) {
                    return $p;
                }   
            }
            return FALSE;
        }
    }