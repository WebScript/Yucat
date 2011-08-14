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
        
        public function banners($page = 1) {
            $rank = new \Model\Profile();
            $this->template->rank = $rank->getUserRank($this->isLogged()->rank, $this->template);
            $this->template->peer_day = $rank->getCreditPeerDay(UID);
            $this->template->date = new \inc\Date();
            
            $statistic = new \Model\Statistic();
            $banners = $this->db()->tables('banners')->where('UID', UID)->fetchAll();
            $this->template->graph = $statistic->createGraph($banners, 'date');

            $menu = new \Model\Menu();
            $this->template->map = $menu->pager($this->db()->tables('banners')->where('UID', UID)->num_rows());
            $this->template->table_content = $this->db()->tables('banners')->where('UID', UID)->limit($page - 1, $page + 50)->fetchAll();
            \inc\Ajax::setMode(TRUE);
        }
    }