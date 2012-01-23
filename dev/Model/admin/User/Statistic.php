<?php
    /**
     * Statistic
     *
     * @category   Yucat
     * @package    Model
     * @name       Statistic
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.2
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
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