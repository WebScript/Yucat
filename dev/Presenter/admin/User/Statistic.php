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
     * @version    Release: 0.1.7
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter\admin\User;
    
    use inc\Date;
    
    class Statistic extends \Presenter\BasePresenter {
        public function __construct() {
            parent::__construct();
            $this->forLogged();
            \inc\Router::redirect('User:Statistic:banners', TRUE);
        }
        
        
        public function banners() {
            $rank = new \Model\admin\User\Main();
            $this->template->rank = $rank->getUserRank($this->template->user->rank, $this->template);
            $this->template->peer_day = $rank->getCreditPeerDay(UID);
            $this->template->date = new Date();

            $statistic = new \Model\admin\User\Statistic();
            $banners = $this->db()->tables('banners')->where('UID', UID)->fetchAll();
            $this->template->graph = $statistic->createGraph($banners, 'time');

            $menu = new \Model\admin\Menu();
            $this->template->map = $menu->pager($this->db()->tables('banners')->where('UID', UID)->numRows());
            $this->template->selector = $menu->selectPeerPage();
            $this->template->table_content = $this->db()->tables('banners')->where('UID', UID)->limit(($_GET['page'] - 1) * $_GET['peerPage'], $_GET['peerPage'])->order('id DESC')->fetchAll();
        }
        
        
        public function access() {
            $rank = new \Model\admin\User\Main();
            $this->template->rank = $rank->getUserRank($this->template->user->rank, $this->template);
            $this->template->peer_day = $rank->getCreditPeerDay(UID);
            $this->template->date = new Date();

            $statistic = new \Model\admin\User\Statistic();
            $access = $this->db()->tables('access')->where('UID', UID)->fetchAll();
            $this->template->graph = $statistic->createGraph($access, 'time');

            $menu = new \Model\admin\Menu();
            $this->template->map = $menu->pager($this->db()->tables('access')->where('UID', UID)->numRows());
            $this->template->selector = $menu->selectPeerPage();
            $this->template->table_content = $this->db()->tables('access')->where('UID', UID)->limit(($_GET['page'] - 1) * $_GET['peerPage'], $_GET['peerPage'])->order('id DESC')->fetchAll();
        }
    }