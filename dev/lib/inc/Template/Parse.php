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
            $regular = '([a-zA-z0-9' . preg_quote('_-=<> ()\'"$%@!^&|*') . ']+)';
            
            preg_match_all('/\{' . $regular . '\}/', $haystack, $finded);
            $search = \inc\Arr::arrayKeyReplace($delimiter, $regular, $search);

            foreach($finded as $key => $val) {
                \inc\Diagnostics\Debug::dump($val);
                
                foreach($search as $key2 => $val2) {
                    if(preg_match('/' . $key2 . '/', $val[1])) echo 'ok <br />';
                    else echo 'ne <br />';
                }
            }
                //$search_key = array_search($needle, $search);
                /* potom cey [preg porovnat real z macrim ci exstuje
                 * a potom ho nahradit
                 * Replace %key za regulary
                 * Zobrat array z macrami a rozdelit to podla %key
                 * Potom pouzit array_search a najst ci existuje
                 */

            
            /*foreach($search as $key2 => $val2) {
                    echo $key2 . '<br />';
                    preg_match_all('/' . preg_quote($key2) . '/', $val2, $match);
                    
                }*/
           // \inc\Diagnostics\Debug::dump($match);
           // \inc\Diagnostics\Debug::dump($finded);
        }
    }