<?php
    /**
     * Base model for main functions for this system
     * 
     * @category   Yucat
     * @package    Model
     * @name       BaseModel
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.2.0
     * @link       http://www.yucat.net/documentation
     */

    namespace Model;
    
    class BaseModel {      
        protected function db() {
            return \inc\Db::_init();
        }
    }