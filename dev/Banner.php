<?php header("Content-type: image/png");
/** this script doesn't work! */ exit;


    if(!empty($_GET["id"]) &&
       !empty($_GET["size"]) &&
       is_numeric($_GET["id"]) &&
       is_numeric($_GET["size"]) &&
       $_GET["size"] > 0 &&
       $_GET["size"] < 4) {

        $id = $_GET["id"];
        $ip = $_SERVER["REMOTE_ADDR"];
        $date = Date("d. M. Y");
        $size = $_GET["size"];
        $style = Sec::Bezp($_GET["style"]);

        $dir = "./styles/universal/banners/";
        if(!is_Dir($dir)) Die();
        $img = ImageCreateFrompng($dir.$size.".png");

        $db = new Make;

        //if Doesn't exists user with this id
        if(!$db->uQuery("VIEW", "users", $id)) Die();

        //zapise hraca a ip do DB ak este neni
        $query = $db->object($db->uQuery("VIEWS", "banners", Array("ip" => $ip, "date" => $date, "who" => $id)));
        if(!$query)
        {
                $adress = $_SERVER['HTTP_REFERER'];
                $db->uQuery("ADD", "banners", Array(
                    "who" => $id,
                    "ip" => $ip,
                    "date" => $date,
                    "size" => $size,
                    "web" => $adress
                ));  

                $credit = ($size / COST_BANNER) + $db->uQuery("VIEW", "users", $id)->credit;
                $db->uQuery("UPDATE", "users", Array("credit" => $credit), Array("id" => $id));
        }
    }
    
imagepng($img);
imagedestroy($img);
?>