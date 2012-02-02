<?php
    /**
     *
     *
     * @category   Yucat
     * @package    Admin\User
     * @name       Statistic
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.2
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin\User;
    
    use inc\Date;
    
    class Statistic extends \Model\BaseModel {
        
        public function createGraph(array $input, $what, $out = NULL) {
            $str = array();
            if($out === NULL) {
                $out = array(
                '01' => 0,
                '02' => 0,
                '03' => 0,
                '04' => 0,
                '05' => 0,
                '06' => 0,
                '07' => 0,
                '08' => 0,
                '09' => 0,
                '10' => 0,
                '11' => 0,
                '12' => 0
                );
            }
            
            foreach($input as $val) {
                if(Date::getParam($val->$what, 'Y') == date('Y')) {
                    $out[Date::getParam($val->$what, 'm')]++;
                }
            }

            $str[] = 'var d1 = [ ';
            foreach($out AS $key => $val) {
                $str[] = '[' . $key . ', ' . $val . '],';
            }
            $str[] = '];';
            return implode('', $str);
        }
    }