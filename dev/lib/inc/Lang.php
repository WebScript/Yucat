<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    namespace inc;

    class Lang {
        private static $__instance = FALSE;
        private static $language = array();
        private static $user;
        public static $avaiable_language = array();
        
        const UID = UID;
        const LANG_DIR = LANG_DIR;
        const LANG = LANG;
        
        
        /**
         * Find avaiable language and added it to varialble $avaiable_language
         */
        private function __construct() {
            self::$user = db::_init()->uQuery(db::VIEWS, db::USERS, self::UID);
            
            if(is_dir(self::LANG_DIR.self::LANG) && file_exists(self::LANG_DIR.self::LANG.'/index.php')) {
                include(self::LANG_DIR.self::LANG.'/index.php');
                self::$language = $lang;
            }

            $dir = OpenDir(self::LANG_DIR);
            while($name = ReadDir($dir)) {            
                $name_dir = self::LANG_DIR.$name;

                if($name == '.' || $name == '..' || !is_dir($name_dir)) continue;
                if(file_exists($name_dir.'/info.php')) {
                    include($name_dir.'/info.php');
                    self::$avaiable_language = array_merge(self::$avaiable_language, array($name => $this_lang));
                }
            }
        }
        
        
        public static function _init() {
            if(self::$__instance == NULL) {
                self::$__instance = new Lang();
            }
            return self::$__instance;
        }

        
        /**
         * Trnaslate language defines to text
         * @param string $define define
         * @return string text
         */
        public function translate($define) {
            if(Array_Key_Exists($define, self::$language)) {
                $define = self::$language[$define];
            }
            return $define;
        }

        
        /**
         * With this function you can open page and translate all language defines
         * @param string $open_page path to file
         */
        public function openPage($open_page) {
            $db = db::_init();
            $user = self::$user;
            $path = STYLE_DIR.$open_page;
            $x = SubStr_Count($open_page, '/');

            if($x) for($i=0;$i<$x;$i++) $open_page = SubStr($open_page, StrPos($open_page, '/')+1);
            $cache = CACHE_DIR.$open_page.'_'.Rand(1, 100000);

            $file = fopen($path, 'r');
            $content = fread($file, filesize($path));
            fclose($file);

            foreach(self::$language AS $param => $value) {
                $content = str_replace('{'.$param.'}', $value, $content);
            }

            $file = fopen($cache, 'w+');
            if(file_exists($cache)) {
                fwrite($file, $content);
                fclose($file);

                include($cache);
                unlink($cache);
            }
        }
    }