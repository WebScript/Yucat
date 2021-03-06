<?php
    /**
     * Authentification - Logged
     *
     * @category   Yucat
     * @package    Admin
     * @name       Logged
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.5
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter\admin;
    
    use inc\Ajax;
    use inc\Router;
    use inc\Template\Core;
    
    class Logged extends \Presenter\BasePresenter {
        public function __construct() {
            parent::__construct();

            if(!UID){
                return;
            }
            
            $dirs = Router::_init()->getParam('dir');
            $dirs = isset($dirs['d1']) ? $dirs['d1'] : NULL;
            if($dirs == 'SAMP') {
                $menu = Array(
                    '_MENU_MAIN' => Array(
                        '_MENU_PROFILE' => 'Server:SAMP:Main:profile:' . SID
                        ),
                    '_MENU_SAMP_CONFIG' => Array(
                        '_MENU_SAMP_CONFIG' => 'Server:SAMP:Config:config:' . SID,
                        '_MENU_SAMP_CHECK' => 'Server:SAMP:Config:check:' . SID,
                        '_MENU_SAMP_REINSTALL' => 'Server:SAMP:Config:reinstall:' . SID
                        ),
                    '_MENU_SAMP_LOGS' => Array(
                        '_MENU_SAMP_SLOG' => 'Server:SAMP:Logs:serverLog:' . SID,
                        '_MENU_SAMP_BANLIST' => 'Server:SAMP:Logs:banlist:' . SID
                    ),
                    '_MENU_SAMP_FILES' => Array(
                        '_MENU_SAMP_GM' => 'Server:SAMP:Files:gamemodes:' . SID,
                        '_MENU_SAMP_FS' => 'Server:SAMP:Files:filterscripts:' . SID,
                        '_MENU_SAMP_SC' => 'Server:SAMP:Files:scriptfiles:' . SID,
                        '_MENU_SAMP_NPC' => 'Server:SAMP:Files:npcmodes:' . SID,
                        '_MENU_SAMP_PLUGINS' => 'Server:SAMP:Files:plugins:' . SID
                        ),
                    '_MENU_SAMP_SSTATUS' => Array(
                        '_MENU_SAMP_ISS' => 'Server:SAMP:Status:image:' . SID,
                        '_MENU_SAMP_TSS' => 'Server:SAMP:Status:text:' . SID
                    ),
                    
                    
                    '_MENU_BACK' => 'User:Server:view'
                    
                    );
            } else {
                $menu = Array(
                    '_MENU_MAIN' => Array(
                        '_MENU_NEWS' => 'User:Main:news',
                        '_MENU_PROFILE' => 'User:Main:profile'
                        ),
                    '_MENU_STATISTIC' => Array(
                        '_MENU_ACCESS_LOG' => 'User:Statistic:access',
                        '_MENU_BANNERS' => 'User:Statistic:banners'
                        
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