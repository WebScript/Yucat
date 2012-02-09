<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin
     * @name       Menu
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.5
     * @link       http://www.yucat.net/documentation
     */

    namespace Model\admin;
    
    use inc\Router;
    use inc\Diagnostics\Excp;
    
    class Menu extends \Model\BaseModel {
        public static function createMenu(array $array, $translate) {
            $router = Router::_init();
            
            $url = implode(':', $router->getParam('dir')) . ':' . $router->getParam('class') . ($router->getParam('method') ? ':' . $router->getParam('method') . ($router->getParam('params') ? ':' . implode(':', $router->getParam('params')) : '') : '');
            $menu = '<ul id="main-menu" class="radius-top clearfix">';

             foreach($array AS $key => $val) {
                  $menu .= '<li><a href="javascript:changePage(\'' . Router::traceRoute(is_array($val) ? $val[key($val)] : $val) . '\');"'
                        . (is_array($val) && in_array($url, $val) ? 'class="active submenu-active"' : '')
                        . '><img src="' . PROTOCOL . Router::getDomain() . '/styles/' . STYLE . '/' . Router::_init()->getParam('subdomain') . '/theme/img/' . $key . '.png" alt="' . $translate[$key]
                        . '" /><span>' . $translate[$key] . '</span>' 
                        . (is_Array($val) && in_array($url, $val) ? '<span class="submenu-arrow"></span>' : '')
                        . '</a></li>';

                  if(is_Array($val) && in_array($url, $val)) {
                      $sub_menu = '<ul id="sub-menu" class="clearfix">';

                      foreach($val AS $param => $val2) {
                          if(!isset($translate[$param])) new Excp ('E_ISE', 'E_MISSING_MENU_TRANSLATE');
                          
                          $sub_menu .= '<li><a href="javascript:changePage(\'' . Router::traceRoute($val2) . '\');"' . ($url == $val2 ? 'class="active"' : '')
                                    . '>' . $translate[$param] . '</a></li>';
                      }
                      $sub_menu .= '</ul>';
                  }
             }

             $menu .= '</ul>';
             return $menu . (isset($sub_menu) ? $sub_menu : '');
        }
        
        
        public static function pager($countPages) {
            $router = Router::_init();
            $listPeerPage = isset($_GET['peerPage']) && is_numeric($_GET['peerPage']) ? $_GET['peerPage'] : 20;
            $thisPage = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
            
            $out = array(); 
            $thisAddress = $router->traceRoute($router->getLink());
            $count = ceil($countPages / $listPeerPage);
            
            if(!$count) $count = 1;
            if($thisPage < 1) $router->redirect($thisAddress);
            elseif($thisPage > $count) $router->redirect($thisAddress);
            
            $out[] = '<form name="pager" method="GET" action="' . $thisAddress . '">';
            $out[] = '<input type="hidden" name="page" value="' . $thisPage . '" />';
            $out[] = '<input type="hidden" name="peerPage" value="' . $listPeerPage . '" />';

            if($thisPage > 1) {
                $out[] = '<li><a href="javascript:cp(\'' . ($thisPage - 1) . '\');" class="page radius">Previous</a></li>';
                
                if($thisPage <= 4) {
                    if($thisPage > 1) $out[] = '<li><a href="javascript:cp(\'1\');" class="page radius">1</a></li>';
                    if($thisPage > 2) $out[] = '<li><a href="javascript:cp(\'2\');" class="page radius">2</a></li>';
                    if($thisPage > 3) $out[] = '<li><a href="javascript:cp(\'3\');" class="page radius">3</a></li>';
                } elseif($thisPage > 4) {
                    $out[] = '<li><a href="javascript:cp(\'1\');" class="page radius">1</a></li>';
                    $out[] = '<li><a href="javascript:cp(\'2\');" class="page radius">2</a></li>';
                    $out[] = ' ... ';
                    $out[] = '<li><a href="javascript:cp(\'' . ($thisPage - 1) . '\');" class="page radius">' . ($thisPage - 1) . '</a></li>';
                }
            }
            
            $out[] = '<li><span class="page-active radius">' . $thisPage . '</span></li>';
            
            if($thisPage < $count) {
                if($thisPage > ($count - 4)) {
                    if($thisPage <= ($count - 3)) $out[] = '<li><a href="javascript:cp(\'' . ($count - 2) . '\');" class="page radius">' . ($count - 2) . '</a></li>';
                    if($thisPage <= ($count - 2)) $out[] = '<li><a href="javascript:cp(\'' . ($count - 1) . '\');" class="page radius">' . ($count - 1) . '</a></li>';
                    if($thisPage <= ($count - 1)) $out[] = '<li><a href="javascript:cp(\'' . $count . '\');" class="page radius">' . $count . '</a></li>';
                } elseif($thisPage <= ($count - 4)) {
                    $out[] = '<li><a href="javascript:cp(\'' . ($thisPage + 1) . '\');" class="page radius">' . ($thisPage + 1) . '</a></li>';
                    $out[] = ' ... ';
                    $out[] = '<li><a href="javascript:cp(\'' . ($count - 1) . '\');" class="page radius">' . ($count - 1) . '</a></li>';
                    $out[] = '<li><a href="javascript:cp(\'' . $count . '\');" class="page radius">' . $count . '</a></li>';
                }
                $out[] = '<li><a href="javascript:cp(\'' . ($thisPage + 1) . '\');" class="page radius">Next</a></li>';
            }
            
            $out[] = '<li><span class="page-inactive radius">Page ' . $thisPage . ' of ' . $count . '</span></li>';
            
            $out[] = '</form>';
            $out[] = '<script> function cp(value) {$("input:hidden[name=page]").val(value); var xy = $("form[name=pager]"); xy.submit(changePage(xy[0].action + \'?\' + $(xy[0].elements).serialize()));}</script>';
            
            return implode('', $out);
        }
        
        
        public function selectPeerPage() {
            $p = $_GET['peerPage'];
            
            return '
            <form name="peerPageSelector" action="' . Router::traceRoute(Router::_init()->getLink()) . '" method="GET">
                <select id="select-view" name="select-view">
                    <option value="5" ' . ($p == 5 ? 'selected' : '') . '>Show 5</option>
                    <option value="10" ' . ($p == 10 ? 'selected' : '') . '>Show 10</option>
                    <option value="25" ' . ($p == 25 ? 'selected' : '') . '>Show 25</option>
                    <option value="50" ' . ($p == 50 ? 'selected' : '') . '>Show 50</option>
                    <option value="100" ' . ($p == 100 ? 'selected' : '') . '>Show 100</option>
                    <option value="5000" ' . ($p == 5000 ? 'selected' : '') . '>Show All</option>
                </select>
            </form> 
            <script>$("#select-view").click(function() {return false;}); $("#select-view option").click(function() {var f = $("form[name=peerPageSelector]"); changePage(f[0].action + \'?\' + $(f[0].elements).serialize())}); </script>';
        }
    }