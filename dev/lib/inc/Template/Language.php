<?php
    /**
     * Language system
     *
     * @category   Yucat
     * @package    Includes\Template
     * @name       Language
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.2
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     * 
     * @todo Dorobit dokumentaciu
     */

    namespace inc\Template;
    
    class Language {
        
        private $avaiable_languages = array();


        public function __construct($defaultLang = NULL) {
            $dir = opendir(ROOT . LANG_DIR);
            
            while($langs = readdir($dir)) {
                if($langs == '.' || $langs == '..') continue;
                
                include_once(ROOT . LANG_DIR . $langs . '/info.php');
                $this->avaiable_languages = array_merge($this->avaiable_languages, array($langs => $thisLang));
            }
            
            list($lang, $null) = explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'], 2);
            
            if(array_key_exists($defaultLang, $this->avaiable_languages)) {
                $_SESSION['lang'] = $defaultLang;
            } elseif(array_key_exists($lang, $this->avaiable_languages)) {
                $_SESSION['lang'] = $lang;
            } elseif($_SESSION['lang'] !== CFG_DEF_LANG) {
                $_SESSION['lang'] = CFG_DEF_LANG;
            }
            
            /** Define language */
            define('LANG', $_SESSION['lang']);
        }
        
        
        public function getAvaiableLang() {
            return $this->avaiable_languages;
        }
        
        
        public static function getTranslate($name) {
            $filename = ROOT . LANG_DIR . LANG . '/' . $name . '.php';
            if(file_exists($filename)) {
                include_once(ROOT . LANG_DIR . LANG . '/' . $name . '.php');
                if(!isset($translate) || !is_array($translate)) {
                    $translate = array();
                } else {
                    foreach($translate as $key => $val) {
                        $trsl['_' . $key] = $val;
                    }
                    $translate = $trsl;
                }
            } else {
                $translate = array();
            }
            return $translate;
        }
    }