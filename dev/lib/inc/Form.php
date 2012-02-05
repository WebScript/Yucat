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
     * @version    Release: 0.2.0
     * @link       http://www.yucat.net/documentation
     * 
     * @todo to validateData() add check for telephone number and website
     * @todo Pridat kontrolu do validateData() ci je v selecte iba povolena hodnota
     */

    namespace inc;
    
    class Form {
        /** @var array All forms data */
        private $form = array();
        /** @var string Last created form */
        private $last;
        
        
        /**
         * Set action of form
         * 
         * @param string $action action e.g. Users:Main:profile
         * @return Form resource of this class 
         */
        public function setAction($action) {
            $this->form['action'] = Router::traceRoute($action);
            return $this;
        }
        
        
        /**
         * Set method of form
         * 
         * @param string $method GET/POST
         * @return Form resource of this class 
         */
        public function setMethod($method) {
            if(strtolower($method) == 'post' || strtolower($method) == 'get') {
                $this->form['method'] = $method;
            } else {
                new Diagnostics\Excp('E_ISE', 'E_ILEGAL_METHOD');
            }
            return $this;
        }
        
        
        /**
         * Add elelment to $this->form. This element is called in viewer and checking
         * in Presenter
         * 
         * @param string $name Name and id of element
         * @param string $type type of element e.g. select, text, password, etc.
         * @param array $array Array of options for select
         * @return Form resource of this class 
         */
        public function addElement($name, $type, array $array = NULL) {
            $this->last = $name;
            
            if($type === 'select') {
                $option = '';
                foreach($array as $key => $val) {
                    $option .= '<option value="' . $key . '">' . $val . '</option>';
                }
                $this->form[$name] = array('name' => $name, 'value' => $option);
            } else {
                $this->form[$name] = array('name' => $name, 'type' => $type);
            }
            return $this;
        }
        
        
        /**
         * Set minimal and maximal length of specified string
         * 
         * @param integer $minLength Minimal length
         * @param integer $maxLength Maximal length
         * @return Form resource of this class 
         */
        public function setLength($minLength, $maxLength = 0) {
            if(is_numeric($minLength) && is_numeric($maxLength)) {                
                $this->form[$this->last]['minLength'] = $minLength;
                $this->form[$this->last]['maxLength'] = $maxLength;
            } else new Diagnostics\Excp('E_ISE', 'E_NO_RETYPE');
            return $this;
        }
        
        
        /**
         * Set default value
         * 
         * @param string $set Default value
         * @return Form resource of this class 
         */
        public function setValue($set) {
            if(isset($this->form[$this->last]['value'])) {
                if(is_array($set)) {
                    $key = key($set);
                    $val = reset($set);
                    $this->form[$this->last]['value'] = str_replace(
                            '<option value="' . $key . '">' . $val . '</option>', 
                            '<option value="' . $key . '" selected>' . $val . '</option>', 
                            $this->form[$this->last]['value']);
                } else new Diagnostics\Excp('E_ISE', 'E_DEFAULT_VALUE');
            } else {
                $this->form[$this->last]['value'] = $set;
            }
            return $this;
        }
        
        
        /**
         * Set type of error
         * 
         * @param string $type Type of error
         * @return Form resource of this class 
         */
        public function setType($type) {
            $type = strtolower($type);
            if($type == 'text' || $type == 'number' || $type == 'password' ||
                    $type == 'website' || $type == 'email' || $type == 're') {
                $this->form[$this->last]['matchType'] = $type;
            }
            return $this;
        }
        
        
        /**
         * Set error message for failure conditions
         * 
         * @param string $message Error message
         * @return Form resource of this class 
         */
        public function setErrorMessage($message) {
            $this->form[$this->last]['errorMessage'] = $message;
            return $this;
        }
        
        
        /**
         * return array of all forms data
         * 
         * @return array forms data
         */
        public function sendForm() {
            return $this->form;
        }
        
        
        /**
         * Validate input data
         * 
         * @param string $errorMessage Error message for dialog if is sent form
         * @return array 
         */
        public function validateData($errorMessage = NULL) {
            $return = array();

            if(Arr::isInArray($_POST, $this->form)) {
                foreach($this->form as $key => $val) {
                    $name = $val['name'];
                    if($key == 'action' || $key == 'method' || !isset($_POST[$name])) continue;
                    
                    if(isset($val['minLength']) && strlen($_POST[$name]) < $val['minLength']) {
                        $out = array($name => array('status' => 'error'));
                    } elseif(isset($val['maxLength']) && $val['maxLength'] >= $val['minLength']
                            && strlen($_POST[$name]) > $val['maxLength']) {
                        $out = array($name => array('status' => 'error'));
                    } elseif(isset($val['matchType']) && $val['matchType'] == 'text'
                            && !preg_match('/^[A-Z][a-z]+$/', $_POST[$name])) {
                        $out = array($name => array('status' => 'error'));
                    } elseif(isset($val['matchType']) && $val['matchType'] == 'number'
                            && !preg_match('/^[0-9 ]+$/', $_POST[$name])) {
                        $out = array($name => array('status' => 'error'));
                    } elseif(isset($val['matchType']) && $val['matchType'] == 'email'
                            && !Security::checkEmail($_POST[$name])) {
                        $out = array($name => array('status' => 'error'));
                    } elseif(isset($val['matchType']) && $val['matchType'] == 're'
                            && isset($_POST[$val['value']]) 
                            && $_POST[$name] !== $_POST[$val['value']]) {
                        $out = array($name => array('status' => 'error'));
                    } else {
                        $out = array($name => array('status' => 'ok'));
                    }

                    if($out[$name]['status'] == 'error' && isset($val['errorMessage'])) {
                        $out[$name] = array('status' => 'error', 'message' => $val['errorMessage']);
                    }
                    $return = array_merge($return, $out);
                }

                if($errorMessage && \inc\Arr::isInExtendedArray($return, 'error')) {
                    $return = array_merge($return, array('dialogError' => $errorMessage));
                }
            } else {
                new Dialog($errorMessage ? : 'Error', Dialog::DIALOG_ERROR);
            }
            return $return;
        }
        
        
        /**
         * Check data validation
         * 
         * @return BOOL
         */
        public function isValidData() {
            $input = $this->validateData();
            
            if(\inc\Arr::isInExtendedArray($input, 'error') === FALSE) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }