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
     * @version    Release: 0.0.5
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Model;
    
    class Menu extends \Model\BaseModel {
        
        public static function createMenu(array $array, $translate) {
            $url = implode(':', \inc\Router::getAddress());
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
        
        
        public static function pager($countPages, $listPeerPage = 50) {
            $out = array(); 
            $thisPage = \inc\Router::getAddress();
            $thisPage = end($thisPage);
            if(!is_numeric($thisPage)) {
                $thisPage = 1;
                $address = 'banners/';
            } else {
                $address = '';
            }
            $count = ceil($countPages / $listPeerPage);
            
            $link = implode(':', array_slice(\inc\Router::getAddress(), 0, 2));
            if($thisPage < 1) \inc\Router::redirect($link);
            if($thisPage > $count) \inc\Router::redirect($link);

            if($thisPage > 1) {
                $out[] = '<li><a href="' . $address . ($thisPage - 1) . '" class="page radius">Previous</a></li>';
                
                if($thisPage <= 4) {
                    if($thisPage > 1) $out[] = '<li><a href="' . $address . '1" class="page radius">1</a></li>';
                    if($thisPage > 2) $out[] = '<li><a href="' . $address . '2" class="page radius">2</a></li>';
                    if($thisPage > 3) $out[] = '<li><a href="' . $address . '3" class="page radius">3</a></li>';
                } elseif($thisPage > 4) {
                    $out[] = '<li><a href="' . $address . '1" class="page radius">1</a></li>';
                    $out[] = '<li><a href="' . $address . '2" class="page radius">2</a></li>';
                    $out[] = ' ... ';
                    $out[] = '<li><a href="' . $address . ($thisPage - 1) . '" class="page radius">' . ($thisPage - 1) . '</a></li>';
                }
            }
            
            $out[] = '<li><span class="page-active radius">' . $thisPage . '</span></li>';
            
            if($thisPage < $count) {
                if($thisPage > ($count - 4)) {
                    if($thisPage <= ($count - 3)) $out[] = '<li><a href="' . $address . ($count - 2) . '" class="page radius">' . ($count - 2) . '</a></li>';
                    if($thisPage <= ($count - 2)) $out[] = '<li><a href="' . $address . ($count - 1) . '" class="page radius">' . ($count - 1) . '</a></li>';
                    if($thisPage <= ($count - 1)) $out[] = '<li><a href="' . $address . $count . '" class="page radius">' . $count . '</a></li>';
                } elseif($thisPage <= ($count - 4)) {
                    $out[] = '<li><a href="' . $address . ($thisPage + 1) . '" class="page radius">' . ($thisPage + 1) . '</a></li>';
                    $out[] = ' ... ';
                    $out[] = '<li><a href="' . $address . ($count - 1) . '" class="page radius">' . ($count - 1) . '</a></li>';
                    $out[] = '<li><a href="' . $address . $count . '" class="page radius">' . $count . '</a></li>';
                }
                $out[] = '<li><a href="' . $address . ($thisPage + 1) . '" class="page radius">Next</a></li>';
            }
            
            $out[] = '<li><span class="page-inactive radius">Page ' . $thisPage . ' of ' . $count . '</span></li>';
            return implode('', $out);
        }
    }