<?php

    namespace inc\Page;

    class OpenPages {
        
        /** @var array of avaiable languages */
        private $avaiableLanguages = array();
        /** @var defines and translations for multi-language website */
        private $translate = array();
        
        private function __construct() {}


        private static function createTranslation() {
            if(is_dir(LANG_DIR.LANG) && file_exists(LANG_DIR.LANG.'/index.php')) {
                include(LANG_DIR.LANG.'/index.php');
                $this->translate = $lang;
            }

            $dir = OpenDir(LANG_DIR);
            while($name = ReadDir($dir)) {            
                $name_dir = LANG_DIR.$name;

                if($name == '.' || $name == '..' || !is_dir($name_dir)) continue;
                if(file_exists($name_dir.'/info.php')) {
                    include_once($name_dir.'/info.php');
                    $this->avaiableLanguages = array_merge($this->avaiableLanguages, array($name => $thisLang));
                }
            }
        }
    }
