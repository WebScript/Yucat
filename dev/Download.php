<?php session_start(); ob_start();
/** this script doesn't work! */
    //Configuration file
   

    if(!$_GET['what'] && !$_GET['sid']) return 0;

    define('UID', $_COOKIE['id']);
    $user = $db->uQuery('VIEW', 'users', UID);

    $db = new db(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_DB);
    $what = $_GET['what'];

    Auth::isLoged();

    if(!$db->q('object', $db->uQuery('VIEWS', 'servers', Array('id' => $_GET['sid'], 'owner' => UID)))) return 0;
    $ssh = new SSH($_GET['sid']);

    if($what == 'glog') {
            header('Content-Description: File Transfer');
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename="server_log.txt"');
            ReadFile($ssh->path.'/server_log.txt');
    } elseif($what == 'blist') {
            header('Content-Description: File Transfer');
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename="samp.ban"');
            ReadFile($ssh->path.'/samp.ban');
    } else if($what == 'backup') {
            header('Content-Description: File Transfer');
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename="backup.tar"');
            ReadFile($ssh->path.'/backup.tar');
    }
?>