<?php
    /**
    * Authentification - Logged
    *
    * @category Yucat
    * @package Presenter
    * @name Logged
    * @author Bloodman Arun
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
            GLOBAL $router;

            if(!UID){
                return;
            }
            
            $dirs = $router->getParam('dir');
            $dirs = isset($dirs['d1']) ? $dirs['d1'] : NULL;
            if($dirs == 'SAMP') {
                $menu = Array(
                    '_MENU_MAIN' => Array(
                        '_MENU_PROFILE' => 'Server:SAMP:Main:profile:' . SID,
                        ),
                    '_MENU_SAMP_CONFIG' => Array(
                        '_MENU_SAMP_CONFIG' => 'Server:SAMP:Config:config' . SID,
                        '_MENU_CHECK' => 'Server:SAMP:Config:check' . SID,
                        '_MENU_REINSTALL' => 'Server:SAMP:Config:reinstall' . SID,
                        ),
                    '_MENU_SAMP_FILES' => Array(
                        '_MENU_SAMP_GM' => 'Server:SAMP:Config:gamemodes' . SID,
                        '_MENU_SAMP_FS' => 'Server:SAMP:Config:filterscripts' . SID,
                        '_MENU_SAMP_SC' => 'Server:SAMP:Config:scriptfiles' . SID,
                        '_MENU_SAMP_NPC' => 'Server:SAMP:Config:npcmodes' . SID,
                        '_MENU_SAMP_PLUGINS' => 'Server:SAMP:Config:plugins' . SID,
                        ),
                    '_MENU_CREDIT' => 'User:Server:view'
                    
                    );
            } else {
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
                        '_MENU_BUY_CREDIT' => 'User:Credit:buy',
                        '_MENU_SEND_CREDIT' => 'User:Credit:send',
                        '_MENU_CODE_CREDIT' => 'User:Credit:code',
                        '_MENU_VIEW_BNS' => 'User:Credit:collected'
                        ),
                    '_MENU_SERVERS' => Array(
                        '_MENU_VIEW_SERVER' => 'User:Server:view',
                        '_MENU_ORDER_SERVER' => 'User:Server:order'
                        )
                    );
            }

            if(Ajax::isAjax()) {
                Ajax::sendHTML(\Model\admin\Menu::createMenu($menu, Core::$translate));
            } else {
                $this->template->__MENU = \Model\admin\Menu::createMenu($menu, Core::$translate);
            }            
        }
    }