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
     * @version    Release: 0.1.0
     * @link       http://www.yucat.net/documentation
     */

    namespace inc;

    class Dialog {
        /** constant of dialog base */
        const DIALOG_BASE = 'dialogBase';
        const DIALOG_ERROR = 'dialogError';
        const DIALOG_SUCCESS = 'dialogSuccess';
        
        
        /**
         * Create and send dialog
         * 
         * @param string $message any message
         * @param constant $type constant of Dialog
         */
        public function __construct($message, $type = self::DIALOG_BASE) {
            if(Ajax::isAjax()) {
                echo json_encode(array($type => $message));
                exit;
            } else {
                echo '<script>alert(\'' . mysql_escape_string($message) . '\');</script>';
                exit;
            }
        }
    }