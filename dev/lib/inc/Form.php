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
    }