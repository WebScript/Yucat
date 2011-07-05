<?php
/**
 * @author Bloodman Arun
 * @copyright Copyright By Bloodman (c) 2011
 * @link http://gshost.eu
 * @site Servers
 */

class Servers
{
    public function Aktivovat()
    {
        foreach($_POST["list"] as $list)
        {
            if(mysql_fetch_object(MySQL::viewServers(Array("id" => $list)))->lock) MySQL::updateServers(Array("lock" => "0"), Array("id" => $list));
            else MySQL::updateServers(Array("lock" => "1"), Array("id" => $list));
        }
        header("Location: index.php?etc=servers");
    }

    public function Reset()
    {
        foreach($_POST["list"] as $list)
        {
            $expirace = date("d-m-Y", time() + (30 * 24 * 60 * 60));
            MySQL::updateServers(Array("end" => $expirace), Array("id" => $list));
        }
        header("Location: index.php?etc=servers");
    }

    public function Delete()
    {
        foreach($_POST["list"] as $list)
        {
            MySQL::updateServers(Array("lock" => "9"), Array("id" => $list));
        }
        header("Location: index.php?etc=servers");
    }
}
?>