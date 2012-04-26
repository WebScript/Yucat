<?php
    /**
     * Caching files and logs
     *
     * @category   Yucat
     * @package    Library
     * @name       Cache
     * @author     Bloodman Arun
     * @copyright  Copyright (c) 2011 - 2012 by Yucat
     * @license    http://www.yucat.net/license GNU GPLv3 License
     * @version    Release: 0.2.3
     * @link       http://www.yucat.net/documentation
     */

     namespace inc;

     final class Cache {
         /** @var string cache folder */
         private $folder;
         
         
         public function __construct($folder) {
             $this->folder = $folder;
         }
         
         
         /**
          * Check if log exists
          * 
          * @param string $logname
          * @return mixed 
          */
         public function existsLog($logname) {
             $file = ROOT . 'temp/' . $this->folder . '/' . $logname;
             
             if(file_exists($file)) {
                 return file($file);
             } else {
                 return array();
             }
         }
         
         
         /**
          * Find if $what is in log $logname
          * 
          * @param string $logname
          * @param atring $what
          * @return mixed 
          */
         public function findInLog($logname, $what) {
             $file = $this->existsLog($logname);
             return $file ? array_search($what, $file) : FALSE;
         }
         
         
         /**
          * Add $what to log $logname
          * 
          * @param string $logname
          * @param string $what 
          */
         public function addToLog($logname, $what) {
             $file = ROOT . 'temp/' . $this->folder . '/' . $logname;

             $file = fopen($file, 'a');
             fwrite($file, $what . "\n");
             fclose($file);
         }
         
         
         /**
          * Create a cache file
          * 
          * @param string $name
          * @param string $content 
          */
         public function createCache($name, $content) {
             $file = fopen(ROOT . 'temp/' . $this->folder . '/' . $name, 'w');
             fwrite($file, $content);
             fclose($file);
         }
         

         /**
          * Delete cache
          * 
          * @param string $name 
          */
         public function deleteCache($name) {
             $name = ROOT . 'temp/' . $this->folder . '/' . $name;
             unlink($name);
         }
     }