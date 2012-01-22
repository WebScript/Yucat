<?php
    /**
     * Class for send dialogs
     *
     * @category   Yucat
     * @package    Library
     * @name       Dialog
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.2
     * @link       http://www.yucat.net/documentation
     * 
     * @todo Dokoncit
     */

    namespace inc;

    class Dialog {
        /** constant of dialog base */
        const DIALOG_BASE = 'dialogBase';
        
        
        /**
         * Create and send dialog
         * 
         * @param string $message any message
         * @param constant $type constant of Dialog
         */
        public function __construct($message, $type = self::DIALOG_BASE) {
            if(Ajax::isAjax()) {
                echo '{"' . $type . '" : "' . Security::protect($message) . '"}';
            } else {
                //echo '<script>apprise(' . Security::protect($message) . ', {"animate":true});</script>';
                d($message);
                //@todo dokoncit
            }
        }
    }