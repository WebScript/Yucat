<?php
/**
 * @author Bloodman Arun
 * @copyright Copyright By Bloodman (c) 2011
 * @link http://gshost.eu
 * @site Report
 */

class ReportRead
{
    public function Hlavny()
    {
        foreach($_POST["list"] AS $list)
        {
            MySQL::updateReports(Array("lock" => "1"), Array("id" => $list));
            header("Location: index.php?etc=reportread");
        }
    }
}
?>