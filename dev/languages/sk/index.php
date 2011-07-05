<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011 
     * @link http://www.gshost.eu/
     */

    if(empty($lang) || !is_Array($lang)) $lang = Array();

    include(dirname(__FILE__).'/Errors.lang.php');
    include(dirname(__FILE__).'/Access.lang.php');
    include(dirname(__FILE__).'/Generally.lang.php');

    include(dirname(__FILE__).'/Users.lang.php');
    include(dirname(__FILE__).'/Menu.lang.php');
    
    //include(dirname(__FILE__).'/SAMP.lang.php');

?>