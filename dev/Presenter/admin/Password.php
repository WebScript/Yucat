<?php
    /**
     * Password
     *
     * @category   Yucat
     * @package    Presenter
     * @name       Login
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.0.1
     */

    namespace Presenter\admin;
    
    class Password extends \Presenter\BasePresenter {
        
        public function __construct() {
            parent::__construct();
            \inc\Router::like('User:Main:login');
        }
        
        public function recovery($hash = '') {
            $password = $this->db()
                    ->tables('lost_passwords')
                    ->where('hash', $hash)
                    ->fetch();
            if($password) {
                $this->db()
                        ->tables('users')
                        ->where('id', $password->UID)
                        ->update(array('passwd' => $password->passwd));
                
                $this->db()
                        ->tables('lost_passwords')
                        ->where('UID', $password->UID)
                        ->delete();
                echo 'Vase nove heslo Vam bolo zaslane na E-mail!';
                
                //@todo pridat posielanie hesla na mial
            } else {
                echo 'Nepodarilo sa zmenit heslo!';
            }
            exit;
        }
        
    }