<?php
    /**
     * Recovery lost password
     *
     * @category   Yucat
     * @package    Presenter
     * @name       Password
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.0.1
     * @link       http://www.yucat.net/documentation
     */

    namespace Presenter\admin;
    
    class Password extends \Presenter\BasePresenter {
        
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