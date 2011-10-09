<?php
    /**
     * Logged - Menu
     *
     * @category   Yucat
     * @package    Model
     * @name       Menu
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Model;
    
    class Menu extends \Model\BaseModel {
        
        public static function createMenu(array $array, $translate) {
            $url = implode(':', array_slice(\inc\Router::getAddress(), 0, 3));
            $menu = '<ul id="main-menu" class="radius-top clearfix">';

             foreach($array AS $key => $value) {
                  $menu .= '<li><a href="javascript:changePage(\''.\inc\Router::traceRoute(is_array($value) ? $value[key($value)] : $value).'\');"'
                        . (is_array($value) && in_array($url, $value) || $url == $value ? 'class="active submenu-active"' : '')
                        . '><img src="/styles/' . STYLE . '/theme/img/' . $key . '.png" alt="' . $translate[$key]
                        . '" /><span>' . $translate[$key] . '</span>' 
                        . (is_Array($value) && in_array($url, $value) || $url == $value ? '<span class="submenu-arrow"></span>' : '')
                        . '</a></li>';
                  if(is_Array($value) && in_array($url, $value)) {
                      $sub_menu = '<ul id="sub-menu" class="clearfix">';

                      foreach($value AS $param => $val) {
                          $sub_menu .= '<li><a href="javascript:changePage(\'' . \inc\Router::traceRoute($val) . '\');"' . ($url == $val ? 'class="active"' : '')
                                    . '>' . $translate[$param] . '</a></li>';
                      }
                      $sub_menu .= '</ul>';
                  }
             }

             $menu .= '</ul>';
             return $menu . (isset($sub_menu) ? $sub_menu : '');
        }
        
        
        
        public static function pager($countPages) {
            $listPeerPage = isset($_GET['peerPage']) && is_numeric($_GET['peerPage']) ? $_GET['peerPage'] : 20;
            $thisPage = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
            
            $out = array(); 
            $thisAddress = \inc\Router::getAddress();
            $count = ceil($countPages / $listPeerPage);
            
            if(!$count) $count = 1;
            if($thisPage < 1) \inc\Router::redirect($thisAddress);
            elseif($thisPage > $count) \inc\Router::redirect($thisAddress);
            
            $out[] = '<form name="pager" method="GET" action="' . \inc\Router::traceRoute(\inc\Router::getAddress()) . '">';
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
            $out[] = '<script> function cp(value) {$("input:hidden[name=page]").val(value); var xy = $("form[name=pager]"); xy.submit(sendGetParams(xy));}</script>';
            
            return implode('', $out);
        }
        
        
        public function selectPeerPage() {
            $p = $_GET['peerPage'];
            
            return '
            <form name="peerPageSelector" action="' . \inc\Router::traceRoute(\inc\Router::getAddress()) . '" method="GET">
                <select id="select-view" name="select-view">
                    <option value="5" ' . ($p == 5 ? 'selected' : '') . '>Show 5</option>
                    <option value="10" ' . ($p == 10 ? 'selected' : '') . '>Show 10</option>
                    <option value="25" ' . ($p == 25 ? 'selected' : '') . '>Show 25</option>
                    <option value="50" ' . ($p == 50 ? 'selected' : '') . '>Show 50</option>
                    <option value="100" ' . ($p == 100 ? 'selected' : '') . '>Show 100</option>
                    <option value="5000" ' . ($p == 5000 ? 'selected' : '') . '>Show All</option>
                </select>
            </form> 
            <script>$("#select-view").click(function() {return false;}); $("#select-view option").click(function() {sendGetParams($("form[name=peerPageSelector]"))}); </script>';
        }
    }