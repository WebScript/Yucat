<?php header('Content-type: image/png');
/** this script doesn't work! */
    if(!$_GET['port'] || !$_GET['ip'] || !$_GET['type']) Die();
    require('./include/Status.inc.php');

    $ip = $_GET['ip'];
    $port = $_GET['port'];

    $image = ImageCreateFrompng('./images/GtaSA.png');
    $white = ImageColorAllocate($image, 255, 255, 255);
    $green = ImageColorAllocate($image, 0, 204, 0);
    $red = ImageColorAllocate($image, 255, 0, 0);

    $font = './styles/universal/font.ttf';
    $st = Status::getServerStatus($_GET['type'], $ip, $port);

    if(!is_Array($st))
    {
            Imagettftext($image, 10, 0, 32, 35, $green, $font, 'IP:');
            Imagettftext($image, 12, 0, 53, 35, $white, $font, $ip.':'.$port);
            Imagettftext($image, 10, 4, 153, 47, $red, $font, 'Offline');
    }else{
            Imagettftext($image, 10, 0, 10, 18, $green, $font, 'Hostname:');
            Imagettftext($image, 10, 0, 80, 18, $white, $font, $st['name']);

            Imagettftext($image, 10, 0, 10, 33, $green, $font, 'IP:');
            Imagettftext($image, 10, 0, 32, 33, $white, $font, $ip.':'.$port);

            Imagettftext($image, 10, 0, 10, 48, $green, $font, 'Players:');
            Imagettftext($image, 10, 0, 70, 48, $white, $font, $st['players'].'/'.$st['max_players']);

            Imagettftext($image, 10, 0, 220, 33, $green, $font, 'Mode:');
            Imagettftext($image, 10, 0, 250, 33, $white, $font, $st['mode']);

            Imagettftext($image, 10, 0, 220, 48, $green, $font, 'Map:');
            Imagettftext($image, 10, 0, 258, 48, $white, $font, $st['map']);
    }
    imagepng($image);
    imagedestroy($image);
?>