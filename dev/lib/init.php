<?php
    /**
     * Main class for auto load all classes, models, includes, etc.
     * In new version is used namespace and is deleted suffix e.g. .inc.php,
     * class.php, etc.
     *
     * @category   Yucat
     * @package    Library
     * @name       init
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.2.0
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.0
     * 
     */


    function __autoload($class) {
        $class = str_replace('\\', '/', $class);

        /* Check if class is in inc add lib */
        list($check) = explode('/', $class, 2);
        if($check == 'inc') {
            $class = 'lib/' . $class;
        }
        
        $dir = ROOT . $class . '.php';
        
        if(file_exists($dir)) {
            require_once($dir);
        }
    }
