<?php
    /**
     * User - News
     *
     * @category   Yucat
     * @package    Presenter\User
     * @name       News
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\User;
    
    class Statistic extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            \inc\Router::redirect('User:Statistic:banners', TRUE);
        }
        
        public function banners() {
            $rank = new \Model\Profile();
            $this->template->rank = $rank->getUserRank($this->isLogged()->rank, $this->template);
            $this->template->peer_day = $rank->getCreditPeerDay(UID);
            $this->template->date = new \inc\Date();
            
            
            
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
//echo date('U', time() + 60 * 60 * 24 * 30 * 2);
                $str = array();
                $banners = $this->db()->tables('banners')->where('UID', UID)->fetchAll();
                foreach($banners as $val) { //echo $val->date;
                    $out[date('m', $val->date)]++;
                }

                $str[] = 'var d1 = [ ';
                foreach($out AS $key => $val) {
                    $str[] = '[' . $key . ', ' . $val . '],';
                }
                $str[] = '];';
                $this->template->graph = implode('', $str);
            
            \inc\Ajax::setMode(TRUE);
        }
    }