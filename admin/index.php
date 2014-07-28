<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 28-7-14
 * Time: 15:52
 */

require_once "adminController.php";

if(isset($_GET['page']))
{
    $c = new AdminController($_GET['page']);
}
else
    $c = new AdminController("facturen");

 