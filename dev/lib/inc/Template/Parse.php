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
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     * 
     * @todo Dorobit dokumentaciu
     */

    namespace inc\Template;
    
    class Parse {
        
        public function parseSpecial($text, array $search, $delimiter = '%key') {
            $macro = $fnc = array();

            foreach($search as $key => $val) {
                $fnc[] = explode($delimiter, $val, 2);
                $mcr = explode($delimiter, $key, 2);
                
                if(isset($mcr[1])) {
                    str_replace('macro', '\inc\Template\Macro::macro', $mcr[1]);
                }
                
                $macro[] = $mcr;
            }

            foreach($macro as $key => $val) {
                if(isset($val[0]) && isset($fnc[$key][0])) {
                    $text = str_replace($val[0], $fnc[$key][0], $text);
                }

                if(isset($val[1]) && isset($fnc[$key][1])) {
                    $text = str_replace($val[1], $fnc[$key][1], $text);
                }
            }

            $text = str_replace('{', '<?php', $text);
            $text = str_replace('}', '?>', $text);
            return $text;
        }
    }