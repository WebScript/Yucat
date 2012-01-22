<?php
    /**
     * Language system
     *
     * @category   Yucat
     * @package    Library\Template
     * @name       Language
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.2.0
     * @link       http://www.yucat.net/documentation
     */

    namespace inc\Template;
    
    use inc\Cookie;
    use inc\Config;
    use \inc\Diagnostics\Excp;
    
    class Language {
        /** @var array all avaiable languages */
        private $avaiable_languages = array();


        /** 
         * Load all languages from files
         */
        public final function __construct() {
            $cookie = Cookie::_init();
            $dir = opendir(LANG_DIR);
            
            while($langs = readdir($dir)) {
                if($langs == '.' || $langs == '..') continue;
                
                include_once(LANG_DIR . $langs . '/info.php');
                $this->avaiable_languages = array_merge($this->avaiable_languages, array($langs => $thisLang));
            }
            
            /* Find and set default language */
            if(!$cookie->getParam('lang')) {
                /* Parse default language from browser */
                list($lang, $null) = explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'], 2);
                /* Get default language from DB */
                if(UID) {
                	$userLang = \inc\Db::_init()->tables('users')->where('id', UID)->fetch();
                	$userLang = $userLang ? $userLang->language : NULL;
                }
                
                if(UID && array_key_exists($userLang, $this->avaiable_languages)) {
                    $cookie->addParam(\inc\Cookie::_init()->myCid, 'lang', $userLang);
                } elseif(array_key_exists($lang, $this->avaiable_languages)) {
                    $cookie->addParam($cookie->myCid, 'lang', $lang); 
                } elseif(!$cookie->getParam('lang') || $cookie->getParam('lang') !== Config::_init()->getValue('default_language')) {
                    $cookie->addParam($cookie->myCid, 'lang', Config::_init()->getValue('default_language'));
                } else {
                    new Excp('E_ISE', 'E_NO_LANG');
                }
            }
            
            /* Define language */
            define('LANG', $cookie->getParam('lang'));
        }
        
        
        /*
         * Return all avaiable languages
         * @return array Avaiable Languages
         */
        public final function getAvaiableLang() {
            return $this->avaiable_languages;
        }
        
        
        /**
         * Translate all variables to text
         * 
         * @param string $name Name of file
         * @return array all translate
         */
        public final function getTranslate($name) {
            $filename = LANG_DIR . LANG . '/' . $name . '.php';
            
            if(file_exists($filename)) {
                include_once($filename);
                if(isset($translate) && is_array($translate)) { 
                    foreach($translate as $key => $val) {
                        $trsl['_' . $key] = $val;
                    }
                    return $trsl;
                } else {
                    new Excp('E_ISE', 'E_MISSING_TRANSLATE');
                    return array();
                }
            } else {
                new Excp('E_ISE', 'E_MISSING_TRANSLATE');
                return array();
            }
        }
        
        
        /**
         * Translate all errors in Exception Handler
         * 
         * @return array
         */
        public final function errorTranslate() {
            $filename = LANG_DIR . LANG . '/Errors.php';
            
            if(file_exists($filename)) {
                include_once($filename);
                if(isset($translate) && is_array($translate)) { 
                    return $trsl;
                } else {
                    new Excp('E_ISE', 'E_MISSING_TRANSLATE');
                    return array();
                }
            } else {
                new Excp('E_ISE', 'E_MISSING_TRANSLATE');
                return array();
            }
            
        }
    }