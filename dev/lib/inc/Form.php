<?php
    /**
     * Form
     *
     * @category   Yucat
     * @package    Library
     * @name       Form
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.6
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;
    
    class Form {
        private $form = array();
        private $last;
        
        
        public function setAction($action) {
            $this->form['action'] = Router::traceRoute($action);
            return $this;
        }
        
        
        public function setMethod($method) {
            $this->form['method'] = $method;
            return $this;
        }
        
        
        /**
         *
         * 
         * @param type $name
         * @param type $type
         * @param array $array
         * @return Form 
         */
        public function addElement($name, $type, array $array = NULL) {
            $this->last = $name;
            
            if($type === 'select') {
                $option = '';
                foreach($array as $key => $val) {
                    $option .= '<option value="' . $key . '">' . $val . '</option>';
                }
                $out = array($name => array('name' => $name, 'value' => $option));
            } else {
                $out = array($name => array('name' => $name, 'type' => $type));
            }
            
            $this->form = array_merge($this->form, $out);
            return $this;
        }
        
        
        
        public function setMinLength($length) {
            $this->form[$this->last] = array_merge(
                    $this->form[$this->last],
                    array('minLength' => $length)
                    );
            return $this;
        }
        
        
        
        public function setMaxLength($length) {
            $this->form[$this->last] = array_merge(
                    $this->form[$this->last],
                    array('maxLength' => $length)
                    );
            return $this;
        }
        
        
        
        public function setValue($set) {
            if(isset($this->form[$this->last]['value'])) {
                if(is_array($set)) {
                    $key = key($set);
                    $val = reset($set);
                    $this->form[$this->last]['value'] = str_replace(
                            '<option value="' . $key . '">' . $val . '</option>', 
                            '<option value="' . $key . '" selected>' . $val . '</option>', 
                            $this->form[$this->last]['value']);
                } else {
                    return FALSE;
                }
            } else {
                $this->form[$this->last] = array_merge(
                        $this->form[$this->last],
                        array('value' => $set)
                        );
            }
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
        
        
        public function validateData($errorMessage = NULL) {
            $input = $_POST;
            $return = array();

            if(!Arr::isInArray($input, $this->form)) {
                return $errorMessage ? array('dialogValue' => $errorMessage) : array('dialogValue' => 'Error');
            }
            
            foreach($this->form as $key => $val) {
                $name = $val['name'];
                if(array_key_exists($name, $input)) {
                    if(isset($val['minLength']) && is_numeric($val['minLength']) 
                            && strlen($input[$name]) < $val['minLength']) {
                        $out = array($name => array('status' => 'error'));
                    } elseif(isset($val['maxLength']) && is_numeric($val['maxLength']) 
                            && strlen($input[$name]) > $val['maxLength']) {
                        $out = array($name => array('status' => 'error'));
                    } 
                   /** I don't known what do these condition...
                       elseif(isset($val['value']) && !preg_match('/' . $input[$name] . '/', $val['value'])) {
                        $out = array($name => array('status' => 'error'));
                    } */
                    else {
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

            if($errorMessage && !$this->isValidData()) {
                $return = array_merge($return, array('dialogValue' => $errorMessage));
            }
            return $return;
        }
        
        
        public function isValidData() {
            $input = $this->validateData();
            
            if(\inc\Arr::isInExtendedArray($input, 'error') === FALSE) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        public function getValue($name) {
            return isset($_POST[$this->form[$name]['name']]) ? $_POST[$this->form[$name]['name']] : NULL;
        }
    }