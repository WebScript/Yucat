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
     * @version    Release: 0.2.6
     * @link       http://www.yucat.net/documentation
     * 
     * @todo to validateData() add check for telephone number and website
     * @todo Pridat kontrolu do validateData() ci je v selecte iba povolena hodnota
     */

    namespace inc;
    
    use inc\Diagnostics\Excp;
    
    class Form {
        /** @var array All forms data */
        private $form = array(
            'globalError' => array(
                'lengthError' => '',
                'typeError' => ''
            )
        );
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
                new Excp('E_ISE', 'E_ILEGAL_METHOD');
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
                $this->form[$name]['value'] = $option;
            } else {
                $this->form[$name]['type'] = $type;
            }
            
            $this->form[$name] = array_merge($this->form[$name], array(
                'name' => $name, 
                'minLength' => 0, 
                'maxLength' => 0, 
                'lengthError' => '', 
                'typeErrror' => '',
                'matchType' => ''
                ));
            return $this;
        }
        
        
        /**
         * Set minimal and maximal length of specified string
         * 
         * @param integer $minLength Minimal length
         * @param integer $maxLength Maximal length
         * @return Form resource of this class 
         */
        public function setLength($minLength, $maxLength = 0, $error = '') {
            if(is_numeric($minLength) && is_numeric($maxLength)) {              
                $this->form[$this->last]['minLength'] = $minLength;
                /* Check correct length of max length */
                if(strlen($maxLength) >= strlen($minLength)) {
                    $this->form[$this->last]['maxLength'] = $maxLength;
                } else {
                    new Excp('E_ISE', 'E_WRONG_LENGTH');
                }
            } else new Excp('E_ISE', 'E_NO_RETYPE');
            
            if(!$this->form['globalError']['lengthError']) {
                if($error) {
                    $this->form[$this->last]['lengthError'] = $error;
                } else {
                    new Excp('E_ISE', 'E_NOT_SET_MSG');
                }
            }
            
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
                $this->form[$this->last]['value'] = str_replace(
                        '<option value="' . $set . '">', 
                        '<option value="' . $set . '" selected>', 
                        $this->form[$this->last]['value']);
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
        public function setType($type, $error = '') {
            $type = strtolower($type);
            if($type == 'text' || $type == 'number' || $type == 'password' ||
                    $type == 'website' || $type == 'email' || $type == 're') {
                $this->form[$this->last]['matchType'] = $type;
            }
            
            if(!$this->form['globalError']['typeError']) {
                if($error) {
                    $this->form[$this->last]['typeError'] = $error;
                } else {
                    new Excp('E_ISE', 'E_NOT_SET_MSG');
                }
            }
            
            return $this;
        }
        
        
        
        public function setErrorMessage($type, $message) {
            $type = strtolower($type);
            switch($type) {
                case 'length' :
                    $this->form['globalError']['lengthError'] = $message;
                    break;
            }
            
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
            $out = array();
            
            foreach($this->form['globalError'] as $key => $val) {
                if(!$val) continue;
                foreach($this->form as $key2 => $val2) {
                    if($key2 == 'action' || $key2 == 'method' || $key2 == 'globalError') continue;
                    $this->form[$key2][$key] = $val;
                }
            }
            $this->form['globalError'] = array();
            
            if(Arr::isInArray($_POST, $this->form)) {
                foreach($this->form as $key => $val) {
                    if($key == 'action' || $key == 'method' || $key == 'globalError' || !isset($_POST[$val['name']])) continue;
                    $name = $val['name'];
                    
                    if(strlen($_POST[$name]) < $val['minLength']) {
                        $out[$name] = array('status' => 'error', 'message' => $val['lengthError']);
                    } elseif($val['maxLength'] && strlen($_POST[$name]) > $val['maxLength']) {
                        $out[$name] = array('status' => 'error', 'message' => $val['lengthError']);
                    } elseif($val['matchType'] == 'text' && !preg_match('/^[A-Z][a-z]+$/', $_POST[$name])) {
                        $out[$name] = array('status' => 'error', 'message' => $val['typeError']);
                    } elseif($val['matchType'] == 'number' && !preg_match('/^[0-9 ]+$/', $_POST[$name])) {
                        $out[$name] = array('status' => 'error', 'message' => $val['typeError']);
                    } elseif($val['matchType'] == 'email' && !Security::checkEmail($_POST[$name])) {
                        $out[$name] = array('status' => 'error', 'message' => $val['typeError']);
                    } elseif($val['matchType'] == 're' && isset($_POST[$val['value']]) 
                            && $_POST[$name] !== $_POST[$val['value']]) {
                        $out[$name] = array('status' => 'error', 'message' => $val['typeError']);
                    } else {
                        $out[$name] = array('status' => 'ok');
                    }
                }

                if($errorMessage && \inc\Arr::isInExtendedArray($out, 'error')) {
                    $out['dialogError'] = $errorMessage;
                }
            } else {
                new Dialog($errorMessage ? : 'Error', Dialog::DIALOG_ERROR);
            }
            return array('form' => $out);
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