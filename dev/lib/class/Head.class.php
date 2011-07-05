<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

     /**
      * @todo Vytvorit zvl=ast classu na pracu z mini forom
      */
    class Head {
        
        const UID = UID;
        
        private function __construct() {}
        
        
        /**
         * Function for edit profile
         * @param array $array input
         */
        public static function profile(array $array) {
            if(!array_key_exists($array['language'], Lang::$avaiable_language)) Message(ERR_WRONG_LANGUAGE);
            
            unset($array['save']);

            $check = array(
                db::USERS_FIRSTNAME,
                db::USERS_LASTNAME,
                db::USERS_ADDRESS,
                db::USERS_LANGUAGE,
                db::USERS_CITY,
                db::USERS_POSTCODE,
                db::USERS_TELEPHONE,
                db::USERS_WEBSITE
            );
            
            if(!Arr::isInArray($array, $check)) Message(ERR_NO_SET_ALL);
            
            db::init()->uQuery(db::UPDATE, db::USERS, $array, Array(db::USERS_ID => self::UID));
            Auth::access(Auth::HEAD, AC_EDIT_PROFILE);
            Message(SAVE_OK, 3);
        }
        
        
        /**
         * Function for change password
         * @param array $array input
         */
        public static function password(array $array) {
            if(!db::init()->q(db::FETCH_ARRAY, db::init()->uQuery(db::VIEWS, db::USERS, Array(db::USERS_ID => self::UID, db::USERS_PASSWORD => Security::createHash($array['old_pass']))))) Message(ERR_WRONG_PASS);
            if($array['password'] != $array['retry_pass']) Message(ERR_ENTERED_VALUE_EQUAL);

            db::init()->uQuery(db::UPDATE, db::USERS, Array(db::USERS_PASSWORD => Security::createHash($array['password'])), Array(db::USERS_ID => self::UID));
            Auth::access(Auth::HEAD, AC_CHANGE_PASSWORD);
            Message(CHANGE_PASS_OK, 3);
        }
        
        
        /**
         * Function for add message to the chat
         * @param string $message input
         *//*
        public static function add2Chat($message = FALSE) {
            if(!$message) Message(ERR_NO_SET_MESSAGE);
            $user = db::init()->uQuery(db::VIEW, db::USERS, self::UID);
            
            if($user[db::USERS_CHAT]) {
                if(strlen($message) < 2 || strlen($message) > 500) Message(ERR_LARGE_VALUE);

                $date = Date('U', Time());

                db::init()->uQuery(db::ADD, db::CHAT, array(
                    db::CHAT_WHO => self::UID,
                    db::CHAT_DATE => $date,
                    db::CHAT_MESSAGE => $message
                ));

                Auth::access(Auth::HEAD, AC_ADD_CHAT_MESSAGE);
                Page::refresh();
            }else Message(ERR_CHAT_BAN, 1);
        }
        
        
        /**
         * Replace text for img
         * @param string $text text for replace
         * @return replaced text
         *//*
        public static function setSmiles($text) {
            $Sml = Array('...', ':)', ':(', ':D', '8)', ':o', ';(', '(sweat)', ':|', ':*', ':P',
                '(blush)', ':^)', '|-)', '|=(', '(inlove)', ']:)', '(talk)', '(yawn)',
                '(puke)', '(doh)', ':@', '(wasntme)', '(party)', ':S', '(mm)', '8-|',
                ':x', '(hi)', '(call)', '(devil)', '(angel)', '(envy)', '(wait)', '(bear)',
                '(makeup)', '(clap)', '(giggle)', '(think)', '(bow)', '(rofl)', '(whew)',
                '(happy)', '(smirk)', '(nod)', '(shake)', '(punch)', '(emo)', '(ok)', '(N)',
                '(handshake)', '(skype)', '(h)', '(u)', '(m)', '(F)', '(rain)', '(sun)',
                '(time)', '(music)', '(movie)', '(ph)', '(coffe)', '(pizza)', '(cash)',
                '(muscle)', '(cake)', '(beer)', '(D)', '(dance)', '(ninja)', '(*)', '(finger)',
                '(bandit)', '(smooking)', '(toivo)', '(rock)', '(headbang)', '(bug)', '(fubar)',
                '(poolparty)', '(swear)', '(tmi)', '(heidy)', '(MySpace)');

            foreach($Sml AS $param => $value) {
                if($param == '...') continue;
                $text = str_replace ($value, '<img src="http://'.CFG_WEBSITE.'/styles/'.STYLE.'/theme/images/smiley/'.$param.'.gif">', $text);
            }
            return $text;
        }
        
        
        /**
         * You can use in text this function for bold or other font format
         * @param string $text input
         * @return string output 
         *//*
        public static function addSpecialChars($text) {
            $special = Array('[b]' => '<b>',
                             '[/b]' => '</b>',
                             '[u]' => '<u>',
                             '[/u]' => '</u>',
                             '[i]' => '<i>',
                             '[/i]' => '</i>'
                            );

            foreach($special AS $param => $value) {
                $text = str_replace($param, $value, $text);
            }
            return $text;
        }
*/
        
        /**
         * Function for accept rules
         * @param string $accept POST input
         */
        public static function acceptRules($accept = FALSE) {
            if($accept == 'yes') {
                db::init()->uQuery(db::UPDATE, db::USERS, array(db::USERS_RANK => '1'), array(db::USERS_ID => self::UID));
                Auth::access(Auth::HEAD, AC_ACCEPTED_RULES);
                header('location: http://'.CFG_WEBSITE.'/user/');
            }else Message(ERR_RULES_ACCEPT, 1);
        }
    }