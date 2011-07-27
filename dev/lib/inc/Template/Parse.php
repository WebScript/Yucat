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
     * @version    Release: 0.3.2
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc\Template;
    
    class Parse extends Macro {
        
        public function __construct() {
            parent::__construct();
        }
       
        
        
        public function parseTemplate($haystack, array $search, $delimiter = '%key') {
            $regular = '(\/?[a-zA-z0-9' . preg_quote('_-=<> ()\'"$%@!^&|:.*') . ']+)';
            /** List of finded variables */
            preg_match_all('/\{' . $regular . '\}/', $haystack, $finded);
            /** Protect $search var */
            $search = \inc\Arr::arrayKeyReplace($delimiter, $regular, $search);
            $macro = new Macro();
            
            foreach($finded[1] as $key => $val) {
                foreach($search as $key2 => $val2) {
                    if(preg_match('/' . $key2 . '/', $val)) {
                        if(strpos($val, 'macro') !== FALSE) {
                            $str = explode(' ', $val, 2);
                            $params = explode(',', $str[1]);
                            
                            $content = call_user_func_array(array($macro, $str[0]), $params);
                            $haystack = preg_replace('/\{' . $key2 . '\}/', $content, $haystack);
                        } else {
                            $haystack = preg_replace('/\{' . $key2 . '\}/', '<?php ' . $val2 . ' ?>', $haystack);
                        }
                    }
                }
            }
            
            $haystack = preg_replace('/\{$' . $regular . '\}/', '<?php ' . $regular . ' ?>', $haystack);
            
            echo $haystack;
        }
    }