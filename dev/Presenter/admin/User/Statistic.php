<?php
    /**
     * User - News
     *
     * @category   Yucat
     * @package    Presenter\User
     * @name       Statistic
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.7
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\admin\User;
    
    use inc\Date;
    
    class Statistic extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            \inc\Router::like('User:Statistic:banners');
        }
        
        
        public function banners() {
            $rank = new \Model\User\admin\Main();
            $this->template->rank = $rank->getUserRank($this->isLogged()->rank, $this->template);
            $this->template->peer_day = $rank->getCreditPeerDay(UID);
            $this->template->date = new Date();

            $statistic = new \Model\admin\Statistic();
            $banners = $this->db()->tables('banners')->where('UID', UID)->fetchAll();
            $this->template->graph = $statistic->createGraph($banners, 'date');

            $menu = new \Model\admin\Menu();
            $this->template->map = $menu->pager($this->db()->tables('banners')->where('UID', UID)->numRows());
            $this->template->selector = $menu->selectPeerPage();
            $this->template->table_content = $this->db()->tables('banners')->where('UID', UID)->limit(($_GET['page'] - 1) * $_GET['peerPage'], $_GET['peerPage'])->fetchAll();
        }
        
        
        public function access() {
            $rank = new \Model\User\admin\Main();
            $this->template->rank = $rank->getUserRank($this->isLogged()->rank, $this->template);
            $this->template->peer_day = $rank->getCreditPeerDay(UID);
            $this->template->date = new Date();

            $statistic = new \Model\admin\Statistic();
            $access = $this->db()->tables('access')->where('UID', UID)->fetchAll();
            $this->template->graph = $statistic->createGraph($access, 'date');

            $menu = new \Model\admin\Menu();
            $this->template->map = $menu->pager($this->db()->tables('access')->where('UID', UID)->numRows());
            $this->template->selector = $menu->selectPeerPage();
            $this->template->table_content = $this->db()->tables('access')->where('UID', UID)->limit(($_GET['page'] - 1) * $_GET['peerPage'], $_GET['peerPage'])->fetchAll();
        }
    }