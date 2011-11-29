<?php

    namespace Presenter\admin;
    
    class Index extends \Presenter\BasePresenter {
        
        public final function __construct() {
            parent::__construct();
            echo 'hello world';
        }
    }