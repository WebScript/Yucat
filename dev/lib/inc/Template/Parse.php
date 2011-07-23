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
     * @version    Release: 0.2.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc\Template;
    
    class Parse extends Macro {
        
        public function __construct() {
            parent::__construct();
        }
        
        
        /**
         * Parse template witch $search
         * @param string $text
         * @param array $search
         * @param string $delimiter
         * @return string 
         */
        public function parseSpecial($text, array $search, $delimiter = '%key') {
            $macro = $fnc = array();

            foreach($search as $key => $val) {
                $fnnc = explode($delimiter, $val, 2);
                $mcr = explode($delimiter, $key, 2);
                
                if(isset($fnnc[0])) {
                    $fnnc[0] = str_replace('macro', '\inc\Template\Macro::macro', $fnnc[0]);
                }
                
                $fnc[] = $fnnc;
                $macro[] = $mcr;
            }

            foreach($macro as $key => $val) {
                if(isset($val[0]) && isset($fnc[$key][0])) {
                    $text = str_replace('{' . $val[0], '<?php ' . $fnc[$key][0], $text);
                }

                if(isset($val[1]) && isset($fnc[$key][1])) { 
                    $text = str_replace($val[1] . '}', $fnc[$key][1] . ' ?>', $text);
                } else {
                    $text = str_replace($fnc[$key][0] . '}', $fnc[$key][0] . '; ?>', $text);
                }
            }

            //$text = str_replace('{', '<?php ', $text);
            //$text = str_replace('}', '>', $text);
            return $text;
        }
        
        
        
        
        
        public function parseTest($haystack, array $search, $delimiter = '%key') {
            $regular = '([a-zA-z0-9_\-=<>\ \/\(\)\'\"$%@!^&|*]+)';
            preg_match_all('/\{' . $regular . '\}/', $haystack, $finded);
            
            foreach($finded[1] as $val) {
                //echo $val . '<br />';
                
                $search = \inc\Arr::arrayKeyReplace($delimiter, $regular, $search);
                
                foreach($search as $key2 => $val2) {
                    preg_match_all('/' . $key2 . '/', $val2, $match);
                    
                }
                
                
                
                
                //$search_key = array_search($needle, $search);
                /*
                 * Replace %key za regulary
                 * Zobrat array z macrami a rozdelit to podla %key
                 * Potom pouzit array_search a najst ci existuje
                 */
            }
            print_r($match[0][0]);
            \inc\Diagnostics\Debug::dump(array(1 => 'lol', 2 => array('lol' => 'omg', 'ggg' => 'dd')));
            \inc\Diagnostics\Debug::dump($search);
        }
    }