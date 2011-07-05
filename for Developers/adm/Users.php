<?php
/**
 * @author Bloodman Arun
 * @copyright Copyright By Bloodman (c) 2011
 * @link http://gshost.eu
 * @site Users
 */

class Users
{
    public function Pokec()
    {
        foreach($_POST["list"] as $list)
        {
            if(MySQL::viewUser($list)->pokec) MySQL::updateUsers(Array("pokec" => "0"), Array("id" => $list));
            else MySQL::updateUsers(Array("pokec" => "1"), Array("id" => $list));
            header("Location: index.php?etc=users");
        }
    }

    public function Podmienky()
    {
        foreach($_POST["list"] as $list)
        {
            MySQL::updateUsers(Array("podmienky" => "0"), Array("id" => $list));
        }
        header("Location: index.php?etc=users");
    }

    public function Zmazat()
    {
        foreach($_POST["list"] as $list)
        {
            Mailit(MySQL::viewUser($list)->mail, "Zmazanie úètu ".Info::Web(), "Z dvôvodu porušenia podmienok Vám bol zmazaný úèet na hernom hostingu ".Info::Web()."!");
            MySQL::updateServers(Array("lock" => "9"), Array("kdo" => $list));
            MySQL::deletePokec(Array("kdo" => $list));
            MySQL::deleteAccess(Array("kdo" => $list));
            MySQL::deleteBanners(Array("kdo" => $list));
            MySQL::deleteUser($list);
        }
        header("Location: index.php?etc=users");
   }

   public function dobitKredit()
   {
        if(!empty($_POST["dkredit"]) && is_numeric($_POST["dkredit"])) $kredit = $_POST["dkredit"];
        else $kredit = 1;

        foreach($_POST["list"] as $list)
        {
            MySQL::updateUsers(Array("kredit" => (MySQL::viewUser($list)->kredit + $kredit)), Array("id" => $list));
        }
        header("Location: index.php?etc=users");
   }

   public function resetnutKredit()
   {
        foreach($_POST["list"] as $list)
        {
            MySQL::updateUsers(Array("kredit" => "0"), Array("id" => $list));
        }
        header("Location: index.php?etc=users");
   }
}
?>