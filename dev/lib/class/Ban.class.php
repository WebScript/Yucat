<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    class Ban {

        public static function banControl() {
            $ip = $_SERVER['REMOTE_ADDR'];
            $query = db::init()->q('object', db::init()->uQuery('VIEWS', 'banned', Array('ip' => $ip)));

            if($query) {
                if($query->date != 'permanent' && StrToTime($query->date) < Date('U')) db::init()->uQuery('DELETE', 'banned', Array('id' => $query->id));
                else Die('<script> document.write(""); </script><br /><br /><br /><center>
                          <b>'.Lang::init()->translate(HAVE_BEEN_BANNED).' '.$query->message.'</b><br />
                          <b>'.Lang::init()->translate(BAN_TIME).' '.$query->date.'</b></center>');
            }
        }


        public static function addBan($ip, $reason, $time_lenght=FALSE) {
            $date = !$time_lenght ? 'pernament' : Date(Date::date_format, Time() + $time_lenght * 60); //Time lenght(in minutes) * 60 sec
            $reason = Sec::Bezp($reason);

            db::init()->uQuery('ADD', 'banned', Array(
                'ip' => $ip,
                'reason' => $reason,
                'date' => $date
            ));
        }
    }