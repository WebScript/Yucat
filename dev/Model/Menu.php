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
    }