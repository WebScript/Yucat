<?php

    namespace Presenter\admin;
    
    class Index extends \Presenter\BasePresenter {
        
        public final function __construct() {
            parent::__construct();
            \inc\Router::redirect('admin:Login');
        }
    }