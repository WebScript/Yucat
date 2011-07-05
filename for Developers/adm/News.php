<?php
/**
 * @author Bloodman Arun
 * @copyright Copyright By Bloodman (c) 2011
 * @link http://gshost.eu
 * @site News
 */

class News
{
    public function Add()
    {
        global $id;
        if(strlen($_POST["novinka"]) > 100000 || strlen($_POST["novinka"]) < 1 || strlen($_POST["nadpis"]) < 1 || strlen($_POST["nadpis"]) > 30) Die(Sec::Error("Príliš dlhá novinka!", "Error", 1));
        if(MySQL::addNews($id, $_POST["nadpis"], $_POST["novinka"])) Sec::Error("Pridal si novinku", "Ok", 1);
        else Sec::Error("Nepodarilo sa pridat novinku!", "Error", 1);
    }

    public function Save()
    {
        $edit = $_GET["edit"];
        if(strlen($_POST["novinka"]) > 100000 || strlen($_POST["novinka"]) < 1 || strlen($_POST["nadpis"]) < 1 || strlen($_POST["nadpis"]) > 30) Die(Sec::Error("Príliš dlhá novinka!", "Error", 1));
        if(MySQL::updateNews(Array("nadpis" => $_POST["nadpis"], "novinka" => $_POST["novinka"]), Array("id" => $edit))) Sec::Error("Upravil si novinku", "Ok", 1);
        else Sec::Error("Nepodarilo sa upravit novinku!", "Error", 1);

    }

    public function Delete()
    {
        if(empty($_POST["delete"])) Die(Sec::Error("Neboli vybrave novinky pre zmazanie!", "Error", 1));
        foreach($_POST["delete"] AS $del)
        {
            MySQL::deleteNews(Array("id" => $del));
        }

        Sec::Error("Uspesne ste zmazal novinky!", "Ok", 1);
    }
}
?>