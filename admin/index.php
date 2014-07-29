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
}else if(isset($_GET['q']))
{
    $_GET['page'] = chop($_GET['q'], '/');
    $c = new AdminController(chop($_GET['q'], '/'));
}
else
    $c = new AdminController("facturen");

 