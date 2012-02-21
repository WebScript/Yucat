<?php
    /**
     * Parse template, save to cache, include and delete page
     *
     * @category   Yucat
     * @package    Library\Template
     * @name       Parse
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.3.5
     * @link       http://www.yucat.net/documentation
     */

    namespace inc\Template;
    
    class Parse extends Macro {
        /** @var string Regular exxpresions */
        private $regular;
        /** @var array Last called viewer */
        private static $called = array();

        
        /**
         * Set regular expression
         */
        public function __construct() {
            $this->regular = '(\/?[a-zA-z0-9' . preg_quote('_-=<> -.,?!()\'";$%/!^&|:.*') . ']+)';
            parent::__construct();
        }
       
        
        /**
         * Parse template
         * 
         * @param string $haystack HTML template
         * @param array $search Marcos
         * @param string $delimiter Delimiter
         * @return string Parsed templates
         */
        public function parseTemplate($haystack, array $search, $delimiter = '%key') {
            /* List of finded variables */
            preg_match_all('@\{' . $this->regular . '\}@', $haystack, $finded);
            /* Protect $search var */
            $search = \inc\Arr::arrayKeyReplace($delimiter, $this->regular, $search);
            $macro = new Macro();
            
            foreach($finded[1] as $key => $val) {
                foreach($search as $key2 => $val2) {
                    if(preg_match('@' . $key2 . '@', $val)) {
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
                                }// d($content);
                            $haystack = preg_replace('@\{' . $key2 . '\}@', $content, $haystack, 1);
                        } else { 
                            $haystack = preg_replace('@\{' . $key2 . '\}@', '<?php ' . $val2 . ' ?>', $haystack, 1);
                        }
                    }
                }
            }
            //exit;
            return $haystack;
        }
        
        
        /**
         * Set all variables in Template
         * 
         * @param string $template Template
         * @return string Parsed template
         */
        public function setVariable($template) {
            return preg_replace('@\{\$' . $this->regular . '\}@','<?php echo $\\1; ?>', $template);
        }
    }