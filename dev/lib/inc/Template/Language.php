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
     * @version    Release: 0.1.6
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace inc\Template;
    
    class Language {
        
        private $avaiable_languages = array();


        public final function __construct($userLang = NULL) {
            GLOBAL $cookie;
            
            $dir = opendir(LANG_DIR);
            
            while($langs = readdir($dir)) {
                if($langs == '.' || $langs == '..') continue;
                
                include_once(LANG_DIR . $langs . '/info.php');
                $this->avaiable_languages = array_merge($this->avaiable_languages, array($langs => $thisLang));
            }
            
            
            /** Find and set default language */
            if(!$cookie->getParam('lang')) {
                list($lang, $null) = explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'], 2);

                if($userLang !== NULL && array_key_exists($userLang, $this->avaiable_languages)) {
                    $cookie->addParam($cookie->myCid, 'lang', $userLang);
                } elseif(array_key_exists($lang, $this->avaiable_languages)) {
                    $cookie->addParam($cookie->myCid, 'lang', $lang); 
                } elseif(!$cookie->getParam('lang') || $cookie->getParam('lang') !== $GLOBALS['conf']['default_language']) {
                    $cookie->addParam($cookie->myCid, 'lang', $GLOBALS['conf']['default_language']);
                }
            }
            
            /** Define language */
            define('LANG', $cookie->getParam('lang'));
        }
        
        
        
        
        public final function getAvaiableLang() {
            return $this->avaiable_languages;
        }
        
        
        
        
        
        public final function getTranslate($name) {
            $filename = LANG_DIR . LANG . '/' . $name . '.php';
            if(file_exists($filename)) {
                include_once(LANG_DIR . LANG . '/' . $name . '.php');
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