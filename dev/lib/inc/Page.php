<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011
     * @link http://www.gshost.eu/
     */

     /**
      * @todo popisat funkcie
      */

    namespace inc;

    class Page {
         
         private function __construct() {}
         
         
         /**
          * Fake function for __autoload this file
          */
         public static function init() {}
         
         
        
         
         public static function generateURL($url) {
             if(is_string($url)) $url = explode('/', $url);
             return 'http://'.CFG_WEBSITE.'/'.implode('/', $url);
         }
         
         
         public static function goToPage($url, $generate = TRUE) {
             if($generate) header('location: '.self::generateURL($url));
             else header('location: '.$url);
         }
         
         
         /**
          * Create a menu from array
          * @param array $array array of menu
          */
         public static function Menu($array, $url) {
            $url = self::generateURL($url); 
            $etc = self::getAddress(1);
            $menu = '<ul id="main-menu" class="radius-top clearfix">';

             foreach($array AS $param => $value)
             {
                  $menu .= '<li><a href="'.(is_Array($value) ? $url.$value[key($value)] : $url.$value).'"'.(is_Array($value) ? (in_Array($etc, $value) ? 'class="active submenu-active"' : '') : ($etc == $value) ? 'class="active submenu-active"' : '').'><img src="/styles/'.STYLE.'/theme/img/'.$param.'.png" alt="'.Lang::init()->translate($param).'" /><span>'.Lang::init()->translate($param).'</span>'.(is_Array($value) ? (in_Array($etc, $value) ? '<span class="submenu-arrow"></span>' : '') : ($etc == $value) ? '<span class="submenu-arrow"></span>' : '').'</a></li>';
                  if(is_Array($value) && in_Array($etc, $value))
                  {
                      $sub_menu = '<ul id="sub-menu" class="clearfix">';

                      foreach($value AS $param => $val)
                      {
                          $sub_menu .= '<li><a href="'.$url.$val.'"'.($etc == $val ? 'class="active"' : '').'>'.Lang::init()->translate($param).'</a></li>';
                      }
                      $sub_menu .= '</ul>';
                  }
             }

             $menu .= '</ul>';
             echo $menu.$sub_menu;
        }
        
        
        /**
         * Create a autopage table to subpage
         * @param integer $page current page
         * @param integer $count number of rows in DB
         * @param integer $peerPage rows peer page
         * @return string returning html autopage
         * @todo pridat ... medzi vela cisel
         */
        public static function pager($page, $count, $peerPage) {
            if(!is_numeric($peerPage) || !is_numeric($count) || !is_numeric($page)) return FALSE;
            $out = array();
            $add = self::generateURL(self::getAddress(0).'/'.self::getAddress(1).'/');
            $count = ceil(($count / $peerPage));

            if($page < 1) self::goToPage($add, FALSE);
            if($page > $count) self::goToPage($add, FALSE);

            if($page > 1) {
                $out[] = '<li><a href="'.$add.($page - 1).'" class="page radius">Previous</a></li>';
                
                if($page <= 4) {
                    if($page > 1) $out[] = '<li><a href="'.$add.(1).'" class="page radius">1</a></li>';
                    if($page > 2) $out[] = '<li><a href="'.$add.(2).'" class="page radius">2</a></li>';
                    if($page > 3) $out[] = '<li><a href="'.$add.(3).'" class="page radius">3</a></li>';
                } elseif($page > 4) {
                    $out[] = '<li><a href="'.$add.(1).'" class="page radius">'.(1).'</a></li>';
                    $out[] = '<li><a href="'.$add.(2).'" class="page radius">'.(2).'</a></li>';
                    $out[] = ' ... ';
                    $out[] = '<li><a href="'.$add.($page - 1).'" class="page radius">'.($page - 1).'</a></li>';
                }
            }
            
            $out[] = '<li><span class="page-active radius">'.$page.'</span></li>';
            
            if($page < $count) {
                if($page > ($count - 4)) {
                    if($page <= ($count - 3)) $out[] = '<li><a href="'.$add.($count - 2).'" class="page radius">'.($count - 2).'</a></li>';
                    if($page <= ($count - 2)) $out[] = '<li><a href="'.$add.($count - 1).'" class="page radius">'.($count - 1).'</a></li>';
                    if($page <= ($count - 1)) $out[] = '<li><a href="'.$add.$count.'" class="page radius">'.$count.'</a></li>';
                } elseif($page <= ($count - 4)) {
                    $out[] = '<li><a href="'.$add.($page + 1).'" class="page radius">'.($page + 1).'</a></li>';
                    $out[] = ' ... ';
                    $out[] = '<li><a href="'.$add.($count - 1).'" class="page radius">'.($count - 1).'</a></li>';
                    $out[] = '<li><a href="'.$add.($count).'" class="page radius">'.($count).'</a></li>';
                }
                $out[] = '<li><a href="'.$add.($page + 1).'" class="page radius">Next</a></li>';
            }
            
            $out[] = '<li><span class="page-inactive radius">Page '.$page.' of '.$count.'</span></li>';
            return implode('', $out);
        }
        
        
        /**
         * Function for refresh page
         */
        public static function refresh() {
            header('location: '.$_SERVER['REQUEST_URI']);
        }
        
        
        public static function Message($message, $type=FALSE, $refresh=FALSE) {
            switch($type) {
                case '0' : $type = 'msg-error'; break;
                case '1' : $type = 'msg-alert'; break;
                case '2' : $type = 'msg-info'; break;
                case '3' : $type = 'msg-ok'; break;
                case '4' : $type = 'msg-loading'; break;
            }

            echo '<br /><div class="'.$type.'">'.Lang::init()->translate($message).'</div></br>';

            if(!$refresh) $refresh = 2;
            if($refresh != 'no') header('refresh: '.$refresh);
            if(UID) Lang::init()->openPage('page_sub_footer.html');
            Lang::init()->openPage('page_footer.html');
            Die();
        }
     }

     
     
     function Message($message, $type=FALSE, $refresh=FALSE) {
         Page::Message($message, $type, $refresh);
     }