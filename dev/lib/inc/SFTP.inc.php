<?php
    /**
     * @author Bloodman Arun
     * @copyright UWAP 2011
     * @link http://www.gshost.eu/
     */

    class SFTP {
        private static $__instance = FALSE;
        
        public static function init() {
            if(self::$__instance == NULL) {
                self::$__instance = new SFTP();
            }
            return self::$__instance;
        }
        
        
        public function upload($path, $file, $tmp, $size, $maxsize, $suffix = FALSE) {
            $ssh = SSH::init();
            $diro =  OpenDir($ssh->path.$path);

            //Check max size
            while($dir = ReadDir($diro)) $velkost += FileSize($ssh->path.$path.$dir);
            if($size > $maxsize) Message(ERR_MAX_SIZE);
            if(SubStr($file, 0, 1) == '.' || SubStr($file, 0, 1) == '\\' || SubStr($file, 0, 1) == '/' || SubStr($file, 0, 1) == '#') Message(ERR_BAD_NAME);

            if(is_Array($suffix)) {
                foreach($suffix AS $value) {
                    if($value == 'allowed') $i = 1;
                    else if($value == 'denied') $i = 2;

                    if(SubStr($file, -StrLen($value)) == $value) $ok = 1;
                }
                if($i == 1) {if(!$ok) Message(ERR_BAD_SUFFIX);}
                else if($i == 2) {if($ok) Message(ERR_BAD_SUFFIX);}
            }

            if(file_exists($ssh->path.$path.$file)) Message(ERR_FILE_EXISTS);
            if(!move_uploaded_file($tmp, $ssh->path.$path.$file)) Message(ERR_UPLOAD_FTP);
        }
        
        
        public function deleteFile($path, $file) {
            $ssh = SSH::init();

            foreach($file AS $value) {
                if(substr_count($value, '.') || substr_count($value, '..')) continue;
                if(!file_exists($ssh->path.$path.'/'.$value)) Message(ERR_FILE_NO_EXISTS);
                if(is_dir($ssh->path.$path.'/'.$value)) $ssh->exec('rm -r /opt/'.$ssh->server->port.'/'.$path.'/'.$value);
                else unlink($ssh->path.$path.'/'.$value);
            }
        }
        

        public function makeDir($path = FALSE, $name = FALSE) {            
            $name = Sec($name);
            if(!$path || !$name || StrLen($name) < 2 || StrLen($name) > 24 || SubStr_Count($name, '.') || SubStr_Count($name, '\\') || SubStr_Count($name, '/') || SubStr_Count($name, '#')) Message(ERR_BAN_FOLDER_NAME);
            if(is_dir(SSH::init()->path.$path.$name)) Message(ERR_FOLDER_EXISTS);
            if(!mkDir(SSH::init()->path.$path.$name)) Message(ERR_FAILED_CREATE_FOLDER);
        }
        
        
        public function openDir($path, $folder, $default_path) {
            if(SubStr_Count($folder, '/') || SubStr_Count($folder, '\\')) return $default_path;
            if(SubStr_Count($folder, '..')) {
                
                $delete = explode('/', $path);
                end($delete);
                $del = prev($delete);
                
                $path = SubStr($path, 0, -(StrLen($del) + 1));
            
                if(!is_dir(SSH::init()->path.$path)) return $default_path;
            } else {
                if(is_dir(SSH::init()->path.$path.$folder)) $path = $path.$folder.'/';
                else return $default_path;
            }

            if(SubStr($path, 0, StrLen($default_path)) != $default_path) return $default_path;
            return $path;
        }
        
        
        public function readFile($path, $file) {
            $file = Sec($file);
            if(!is_dir(SSH::init()->path.$path.$file) && file_exists($samp->path.$path.$file)) {
                if(!SubStr_Count($file, '..')) {
                    $f = fopen(SSH::init()->path.$path.$file, 'r');
                    
                    if(filesize(SSH::init()->path.$path.$file) > 0) $text = fread($f, filesize(SSH::init()->path.$path.$file));
                    else $text = fread($f, 1);
                
                    fclose($f);
                    return HTMLSpecialChars($text);
                } Message(ERR_NO_OPEN_FILE);
            } Message(ERR_FILE_NO_EXISTS);
        }
        
        
        public function editFile($path, $name, $text) {
            $name = Sec($name);
            if(SubStr_Count($name, '..')) Message(ERR_NO_EDIT_FILE);
            if(!file_exists(SSH::init()->path.$path.$name)) Message(ERR_FILE_NO_EXISTS);

            $f = fopen(SSH::init()->path.$path.$name, 'w');
            fwrite($f, $text);
            fclose($f);
        }
    }