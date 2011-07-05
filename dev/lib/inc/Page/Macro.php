<?php

    class Macro {
        
        private $macroAlias = array();
        private $macroPrimaryFunction = array();
        private $macroSecundaryFunction = array();
        
        private $separateSpecialFunctions = array();
        
   
    
    private function __connect() {
        $this->addSpecialFunction('in.func', '/[a-zA-Z0-9]/');
       
        $this->addMacro('include', 'include in.func');
    }
    
   
    
    
    
    public function specialParse($input) {
        $special = \inc\Arr::arrayKeyReplace($this->separateSpecialFunctions, $this->macroAlias);
        
        
        $out = str_replace('{'.$this->specialFunction.'}', $special, $input);
        
        Debug::dump($input);
        die();
    }
    
    
        /**
         * Add macro to separate template system
         * @param string $alias alias of function e.g. include
         * @param string $primary is primary function of macro e.g. include('');
         * @param string $secundary is secundary function of macro e.g. if primary is if(...): this is set to endif;
         * @return BOOL
         */
        public function addMacro($alias, $primary, $secundary = FALSE) {
            if(!in_array($alias, $this->macroAlias)) {
                array_merge($this->macroAlias, array($alias));
                array_merge($this->macroPrimaryFunction, array($alias => $primary));
                array_merge($this->macroSecundaryFunction, array($alias => $secundary));
            } else {
                return FALSE;
            }
            return TRUE;
        }
    
    
    
        public function addSpecialFunction($alias, $param) {
            array_merge($this->separateSpecialFunctions, array($alias => $param));
            return TRUE;
        }
    

    }