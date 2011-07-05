<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    class Credit {
        private static $__instance = FALSE;

        public static function init() {
            if(self::$__instance == NULL) {
                self::$__instance = new Credit();
            }
            return self::$__instance;
        }

        
        public function useCode($code1 = FALSE, $code2 = FALSE, $code3 = FALSE) {        
            if(!$code1 || !$code2 || !$code3) Message(ERR_NO_SET_ALL);
            if(!Sec::isAlphabet($code1) || !Sec::isAlphabet($code2) || !Sec::isAlphabet($code3)) Message(ERR_WRONG_SET_VALUES);
            if(strlen($code1) != 4 || strlen($code2) != 8 || strlen($code3) != 4) Message(ERR_WRONG_CODE);

            $code = db::init()->object(db::init()->uQuery('VIEWS', 'codecredit', Array('1' => $code1, '2' => $code2, '3' => $code3)));
            if($code->id) {
                if($code->lock) Message(ERR_CODE_ALREADY_USED);

                $credit = db::init()->uQuery('VIEW', 'users', UID)->credit + $code->cost;
                db::init()->uQuery('UPDATE', 'users', Array('credit' => $credit), Array('id' => UID));
                db::init()->uQuery('UPDATE', 'codecredit', Array('lock' => '1'), Array('id' => UID));

                Auth::Access('head', AC_RECHANGE_CREDIT);
                Message(CREDIT_ADD_OK, 3);
            }else Message(ERR_WRONG_CODE);
        }
    }