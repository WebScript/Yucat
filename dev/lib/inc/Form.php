<?php
    /**
     * Form
     *
     * @category   Yucat
     * @package    Includes
     * @name       Form
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     */

    namespace inc;
    
    class Form {
        private $form = array();
        private $last;
        
        
        public function addElement($id, $name, $type) {
            $this->last = $id;
            $this->form = array_merge($this->form, array(
                $id => array(
                    'type' => $type,
                    'name' => $name
                    )
                ));
            return $this;
        }
        
        
        public function setMinLenght($lenght) {
            $this->form[$this->last] = array_merge(
                    $this->form[$this->last],
                    array('minLenght' => $lenght)
                    );
            return $this;
        }
        
        
        public function setMaxLenght($lenght) {
            $this->form[$this->last] = array_merge(
                    $this->form[$this->last],
                    array('maxLenght' => $lenght)
                    );
            return $this;
        }
        
        
        public function setValue($value) {
            $this->form[$this->last] = array_merge(
                    $this->form[$this->last],
                    array('value' => $value)
                    );
            return $this;
        }
        
        
        public function setErrorType($type) {
            $this->form[$this->last] = array_merge(
                    $this->form[$this->last],
                    array('errorType' => $type)
                    );
            return $this;
        }
        
        
        public function setErrorMessage($message) {
            $this->form[$this->last] = array_merge(
                    $this->form[$this->last],
                    array('errorMessage' => $message)
                    );
            return $this;
        }
        
        
        public function sendForm() {
            return $this->form;
        }
        
        
        public function validateData($input = NULL) {
            $input = $input === NULL ? $_GET : $input;
            $return = array();
            
            foreach($this->form as $key => $val) {
                $name = $val['name'];
                if(array_key_exists($name, $input)) {
                    if(isset($val['minLenght']) && is_numeric($val['minLenght']) 
                            && strlen($input[$name]) < $val['minLenght']) {
                        $out = array($name => array('status' => 'error'));
                    } elseif(isset($val['maxLenght']) && is_numeric($val['maxLenght']) 
                            && strlen($input[$name]) > $val['maxLenght']) {
                        $out = array($name => array('status' => 'error'));
                    } else {
                        $out = array($name => array('status' => 'ok'));
                    }
                    
                    /** @todo pridat kontrolu, ci to je text, mail, link alebo nieco ine... */
                    
                    if(is_array($out[$name]) 
                            && $out[$name]['status'] == 'error' 
                            && isset($val['errorMessage'])) {
                        $out[$name] = array_merge($out[$name], array('message' => $val['errorMessage']));
                    }
                    $return = array_merge($return, $out);
                }
            }
            return $return;
        }
        
        
        public function isValidData($input = NULL) {
            $input = $this->validateData($input === NULL ? $_GET : $input);
            
            if(\inc\Arr::isInExtendedArray($input, 'error') === FALSE) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        public function getValue($name) {
            return $_GET[$this->form[$name]['name']];
        }
    }