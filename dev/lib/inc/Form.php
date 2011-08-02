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
     * @version    Release: 0.1.0
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
        
        
        public function validateData(array $input) {
            foreach($this->form as $key => $val) {
                if(array_key_exists($val['name'], $input)) {
                    if(isset($val['minLenght']) && is_numeric($val['minLenght']) 
                            && strlen($input[$val['name']]) < $val['minLenght']) {
                        $error = array('status' => 'error');
                    } elseif(isset($val['maxLenght']) && is_numeric($val['maxLenght']) 
                            && strlen($input[$val['name']]) > $val['maxLenght']) {
                        $error = array('status' => 'error');
                    } else {
                        $error = array('status' => 'ok');
                    }
                    
                    /** @todo pridat kontrolu, ci to je text, mail, link alebo nieco ine... */
                    
                    if($error['status'] == 'error' && isset($val['errorMessage'])) {
                        $error = array_merge($error, array('message' => $val['errorMessage']));
                    }
                    return $error;
                }
            }
            return array();
        }
    }