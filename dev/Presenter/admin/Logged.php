<?php
    /**
    * Authentification - Logged
    *
    * @category Yucat
    * @package Presenter
    * @name Logged
    * @author René Činčura (Bloodman Arun)
    * @copyright Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
    * @license http://www.yucat.net/license GNU GPL License
    * @version Release: 0.0.2
    * @link http://www.yucat.net/documentation
    * @since Class available since Release 0.0.1
    */

    namespace Presenter\admin;
    
    use inc\Ajax;
    use inc\Template\Core;
    
    class Logged extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();

            if(!$this->isLogged()){
                return;
            }
            $this->template->user = $this->isLogged();
            
            $menu = Array(
                '_MENU_MAIN' => Array(
                    '_MENU_NEWS' => 'User:Main:news',
                    '_MENU_PROFILE' => 'User:Main:profile'
                    ),
                '_MENU_STATISTIC' => Array(
                    '_MENU_BANNERS' => 'User:Statistic:banners',
                    '_MENU_ACCESS_LOG' => 'User:Statistic:access'
                ),
                '_MENU_CREDIT' => Array(
                    '_MENU_BUY_CREDIT' => 'credit_buy',
                    '_MENU_CODE_CREDIT' => 'credit_code',
                    '_MENU_SEND_CREDIT' => 'credit_send',
                    '_MENU_VIEW_BNS' => 'banners_view'
                    ),
                '_MENU_ORDER_M' => Array(
                    '_MENU_ORDER_SERVER' => 'order_server',
                    '_MENU_DELETE_SERVER' => 'delete_server'
                    ),
                '_MENU_SERVERS' => 'servers'
                );
                
            
            if(Ajax::isAjax()) {
                Ajax::sendHTML(\Model\admin\Menu::createMenu($menu, Core::$translate));
            } else {
                $this->template->__MENU = \Model\admin\Menu::createMenu($menu, Core::$translate);
            }            
        }
    }