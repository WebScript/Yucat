<?php
    /**
     * Caching files and logs
     *
     * @category   Yucat
     * @package    Includes
     * @name       Cache
     * @author     René Činčura (Bloodman Arun)
     * @copyright  Copyright (c) 2011 Bloodman Arun (http://www.yucat.net/)
     * @license    http://www.yucat.net/license GNU GPL License
     * @version    Release: 0.1.3
     * @link       http://www.yucat.net/documentation
     * @since      Class available since Release 0.1.0
     * 
     * @todo zdokumentovat cache
     */

     namespace inc;

     class Cache {
         private $folder;
         
         public function __construct($folder) {
             $this->folder = $folder;
         }
         
         
         public function existsLog($logname) {
             $file = TEMP . $this->folder . '/' . $logname;
             
             if(file_exists($file)) {
                 return $file;
             } else {
                 return FALSE;
             }
         }
         
         
         public function findInLog($logname, $what) {
             $file = $this->existsLog($logname);
             return $file ? array_search($what, $file) : FALSE;
         }
         
         
         public function addToLog($logname, $what) {
             $file = TEMP . $this->folder . '/' . $logname;

             $file = fopen($file, 'a');
             fwrite($file, $what);
             fclose($file);
         }
         
         
         public function createCache($name, $content) {
             $file = fopen(TEMP . $this->folder . '/' . $name, 'w');
             fwrite($file, $content);
             fclose($file);
         }
         
         /* NOT USED!!
         public function getCache($name) {
             $dir = TEMP . $this->folder . '/' . $name;
             $file = fopen($dir, 'r');
             
             $out = fread($file, filesize($dir));
             fclose($file);
             return $out;
         }*/
         
         
         public function deleteCache($name) {
             $name = TEMP . $this->folder . '/' . $name;
             unlink($name);
         }
         
         
         public function includeCache($name) {
             $name = TEMP . $this->folder . '/' . $name;
             include $name;
         }
     }