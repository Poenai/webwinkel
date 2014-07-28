<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 28-7-14
 * Time: 15:31
 */

require_once dirname(__FILE__)."/../PHPLib/controller.php";
require_once dirname(__FILE__)."/../PHPLib/producten.php";

class AdminController extends Controller {

    public function facturen()
    {

    }

    public function leerlingen()
    {

    }

    public function producten()
    {
        $this->SetVar('producten', Producten::GetAllProducts());
    }

} 