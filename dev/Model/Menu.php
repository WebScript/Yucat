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
        
        
        
        public static function pager($countPages, $listPeerPage = 20) {
            $out = array(); 
            $thisAddress = \inc\Router::getAddress();
            $thisPage = end($thisAddress);
            
            if(!is_numeric($thisPage)) {
                $thisPage = 1;
            } else { 
                array_pop($thisAddress);
            }
            $count = ceil($countPages / $listPeerPage);
            
            if(!$count) $count = 1;
            $address = $GLOBALS['conf']['protocol'] . DOMAIN . '/' . implode('/', $thisAddress) . '/';
            array_pop($thisAddress);

            if($thisPage < 1) \inc\Router::redirect($thisAddress);
            elseif($thisPage > $count) \inc\Router::redirect($thisAddress);
            
            $out[] = '<form name="pager" method="GET" action="' . \inc\Router::traceRoute(\inc\Router::getAddress()) . '">';
            $out[] = '<input type="hidden" name="page" value="1" />';
            $out[] = '<input type="hidden" name="peerpage" value="50" />';

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
            $out[] = '<script> function cp(value) {$("input:hidden[name=page]").val(value); $("form[name=pager]").submit();}</script>';
            
            return implode('', $out);
        }
    }