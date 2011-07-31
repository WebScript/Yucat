<?php
    /**
     * Parse template, save to cache, include and delete page
     *
     * @category   Yucat
     * @package    Includes\Template
     * @name       Parse
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.3.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc\Template;
    
    class Parse extends Macro {
        
        private $regular;
        private static $called = array();

        public function __construct() {
            $this->regular = '(\/?[a-zA-z0-9' . preg_quote('_-=<> ()\'"$%@!^&|:.*') . ']+)';
            parent::__construct();
        }
       
        
        
        public function parseTemplate($haystack, array $search, $delimiter = '%key') {
            /** List of finded variables */
            preg_match_all('/\{' . $this->regular . '\}/', $haystack, $finded);
            /** Protect $search var */
            $search = \inc\Arr::arrayKeyReplace($delimiter, $this->regular, $search);
            $macro = new Macro();
            
            foreach($finded[1] as $key => $val) {
                foreach($search as $key2 => $val2) {
                    if(preg_match('/' . $key2 . '/', $val)) {
                        if(strpos($val, 'macro') !== FALSE) {
                            $str = explode(' ', $val, 2);
                                if(!key_exists($val, self::$called)) {
                                    if(isset($str[1])) {
                                        $params = explode(',', $str[1]);
                                    } else {
                                        $params = array();
                                    }

                                    $content = call_user_func_array(array($macro, $str[0]), $params);
                                    self::$called[$val] = $content;
                                } else {
                                    $content = self::$called[$val];
                                }
                            $haystack = preg_replace('/\{' . $key2 . '\}/', $content, $haystack);
                        } else {
                            $haystack = preg_replace('/\{' . $key2 . '\}/', '<?php ' . $val2 . ' ?>', $haystack);
                        }
                    }
                }
            }
            return $haystack;
        }
        
        public function setVariable($template) {
            return preg_replace('/\{\$' . $this->regular . '\}/','<?php echo $\\1; ?>', $template);
        }
    }