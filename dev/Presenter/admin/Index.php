<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Admin
     * @name       Index
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter\admin;
    
    use inc\Router;
    
    class Index {
        public final function __construct() {
            \inc\Router::redirect('admin:Login');
        }
    }