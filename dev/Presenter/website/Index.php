<?php

    namespace Presenter\website;
    
    class Index  extends \Presenter\BasePresenter {

        public final function __construct() {
            parent::__construct();
            
            $this->template->cau = "ahoj";
        }
    }