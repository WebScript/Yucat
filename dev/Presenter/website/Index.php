<?php
    /**
     * 
     *
     * @category   Yucat
     * @package    Website
     * @name       Index
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     */


    namespace Presenter\website;
    
    
    class Index  extends \Presenter\BasePresenter {
        public final function __construct() {
            parent::__construct();
            
            $this->template->cau = "ahoj";
        }
    }