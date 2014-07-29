<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 28-7-14
 * Time: 15:31
 */

require_once dirname(__FILE__)."/../PHPLib/controller.php";
require_once dirname(__FILE__)."/../PHPLib/producten.php";
require_once dirname(__FILE__)."/../PHPLib/contacten.php";
require_once dirname(__FILE__)."/../PHPLib/factuur.php";

class AdminController extends Controller {

    public function facturen()
    {
        $this->SetVar("facturen", Factuur::GetAllFactuurs());
    }

    public function leerlingen()
    {
        $this->SetVar('leerlingen', Contacten::GetAllContacts());
    }

    public function producten()
    {
        $this->SetVar('producten', Producten::GetAllProducts());
    }

    public function nieuwefactuur()
    {
        $this->SetVar('klanten', Contacten::GetAllContacts());
        $this->SetVar('producten', Producten::GetAllProducts());
    }

} 