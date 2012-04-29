<?php
    /**
     * User
     *
     * @category   Yucat
     * @package    Obj
     * @name       User
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.1.3
     * @link       http://www.yucat.net/documentation
     */


    namespace obj;
    
    class User {
        private $id;
        private $values = array();
        
        
        public function __construct($id) {
            $this->id = $id;
            $this->reload();
        }
        
        
        public function __destruct() {
            $this->save();
        }
        
        
        public function save() {
            \inc\Db::_init()
                    ->tables('users')
                    ->where('id', $this->id)
                    ->update($this->values);
        }
        
        
        public function reload() {
            $user = \inc\Db::_init()
                    ->tables('users')
                    ->where('id', $this->id)
                    ->fetch();

            if($user) {
                $this->values = get_object_vars($user);
            } else throw new \Exception('User doesn\' exist!', 1);
        }
        
        
        public function deleteUser() {
            \inc\Db::_init()
                    ->tables('users')
                    ->where('id', $this->id)
                    ->delete();
        }


        /** Getters */
        public function getUser() {
            return $this->values['user'];
        }
        
        public function getPassword() {
            return $this->values['passwd'];
        }
        
        public function getFisrtname() {
            return $this->values['firstname'];
        }
        
        public function getLastname() {
            return $this->values['lastname'];
        }
        
        public function getStreet() {
            return $this->values['street'];
        }
        
        public function getCity() {
            return $this->values['city'];
        }
        
        public function getPostCode() {
            return $this->values['postcode'];
        }
        
        public function getPhone() {
            return $this->values['telephone'];
        }
        
        public function getCredit() {
            return $this->values['credit'];
        }
        
        public function getLanguage() {
            return $this->values['language'];
        }
        
        public function getStyle() {
            return $this->values['style'];
        }
        
        public function getAvatar() {
            return $this->values['avatar'];
        }
        
        public function getPermissions() {
            return $this->values['permissions'];
        }
        
        public function getRank() {
            return $this->values['rank'];
        }
        
        public function getEmail() {
            return $this->values['email'];
        }
        
        public function getWebsite() {
            return $this->values['website'];
        }
        
        public function getIp() {
            return $this->values['ip'];
        }
        
        public function getLastLogin() {
            return array($this->values['ll1'], $this->values['ll2']);
        }
        
        public function getActivateId() {
            return $this->values['activate_id'];
        }
        
        
        /** Setters */
        public function setUser($value) {
            $this->values['user'] = $value;
        }
        
        public function setPassword($value) {
            $this->values['password'] = $value;
        }
        
        public function setFirstname($value) {
            $this->values['firstname'] = $value;
        }
        
        public function setLastname($value) {
            $this->values['lastname'] = $value;
        }
        
        public function setStreet($value) {
            $this->values['street'] = $value;
        }
        
        public function setCity($value) {
            $this->values['city'] = $value;
        }
        
        public function setPostCode($value) {
            $this->values['postcode'] = $value;
        }
        
        public function setPhone($value) {
            $this->values['telephone'] = $value;
        }
        
        public function setCredit($value) {
            $this->values['credit'] = $value;
        }
        
        public function setLanguage($value) {
            $this->values['language'] = $value;
        }
        
        public function setStyle($value) {
            $this->values['style'] = $value;
        }
        
        public function setAvatar($value) {
            $this->values['avatar'] = $value;
        }
        
        public function setPermissions($value) {
            $this->values['permissions'] = $value;
        }
        
        public function setRank($value) {
            $this->values['rank'] = $value;
        }
        
        public function setEmail($value) {
            $this->values['email'] = $value;
        }
        
        public function setWebsite($value) {
            $this->values['webiste'] = $value;
        }
        
        public function setIp($value) {
            $this->values['ip'] = $value;
        }
        
        public function setLastLogin($value = NULL, $value2 = NULL) {
            if($value !== NULL) {
                $this->values['ll1'] = $value;
            }
            
            if($value2 !== NULL) {
                $this->values['ll2'] = $value2;
            }
        }
        
        public function setActivateId($value) {
            $this->values['activate_id'] = $value;
        }
        
        
        
        
        public function addCredit($credit) {
            $this->setCredit($this->getCredit() + $credit);
            
        }

        
        public function lostPassword(&$hash, &$password) {
            $hash = \inc\String::keyGen(256);
            $password = \inc\String::keyGen(8);
            
            \inc\Db::_init()
                    ->tables('lost_passwords')
                    ->insert(['UID' => $this->id, 'hash' => $hash, 'password' => $password]);
        }
        
        
        public function recoveryPassword($hash) {
            $result = \inc\Db::_init()
                    ->tables('lost_passwords')
                    ->select('password')
                    ->where('hash', $hash)
                    ->fetch();
            if($result) {
                \inc\Db::_init()
                        ->tables('lost_passwords')
                        ->where('hash', $hash)
                        ->delete();
                
                \inc\Db::_init()
                    ->tables('users')
                    ->where('id', $this->id)
                    ->update(['passwd' => $result->password]);
                return TRUE;
            }
            return FALSE;
        }
        
        
        public function getBanners() {
            return \inc\Db::_init()
                    ->tables('banners')
                    ->where('id', $this->id)
                    ->fetchAll();
        }
    }