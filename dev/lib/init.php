<?php
    /**
     * This class manage all classes.
     *
     * @category   Yucat
     * @package    Library
     * @name       init
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.2.1
     * @link       http://www.yucat.net/documentation
     */


    function __autoload($class) {
        /** Replace \ to / and check dir */
        $class = str_replace('\\', '/', $class);

        /** Check if class is in inc  so add lib to path */
        list($check) = explode('/', $class, 2);
        if($check == 'inc') {
            $class = 'lib/' . $class;
        }
        /** Create path to class file */
        $dir = ROOT . $class . '.php';
        
        /** if class exist so include it. */
        if(file_exists($dir)) {
            require_once($dir);
        }
    }
