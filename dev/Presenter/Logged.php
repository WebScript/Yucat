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

    namespace Presenter;
    
    class Logged extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            
            $this->forLogged();
            if(!$this->isLogged()){
                return;
            }
            $this->template->user = $this->isLogged();
            
            $menu = Array(
                'T_MENU_MAIN' => Array(
                    'T_MENU_NEWS' => 'User:Profile:news',
                    'T_MENU_PROFILE' => 'User:Profile:profile'
                    ),
               /* 'T_MENU_STATISTIC' => Array(
                    'bannery' => 'stat_banners',
                    'access log' => 'stat_access'
                ),
                'T_MENU_CREDIT' => Array(
                    'T_MENU_BUY_CREDIT' => 'credit_buy',
                    'T_MENU_CODE_CREDIT' => 'credit_code',
                    'T_MENU_SEND_CREDIT' => 'credit_send',
                    'T_MENU_VIEW_BNS' => 'banners_view'
                    ),
                'T_MENU_ORDER_M' => Array(
                    'T_MENU_ORDER_SERVER' => 'order_server',
                    'T_MENU_DELETE_SERVER' => 'delete_server'
                    ),
                'T_MENU_SERVERS' => 'servers'*/
                );
                
            $this->template->T_MENU_MAIN = 'lol';
            $this->template->T_MENU_NEWS = 'sslol';
            $this->template->T_MENU_PROFILE = 'profile vole';
            
            if(\inc\Ajax::isAjax()) {
                \inc\Ajax::sendHTML(\Model\Menu::createMenu($menu, $this->template));
            } else {
                $this->template->__MENU = \Model\Menu::createMenu($menu, $this->template);
            }            
        }
    }