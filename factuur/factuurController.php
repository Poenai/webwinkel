<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 2-8-14
 * Time: 13:52
 */

require_once dirname(__FILE__)."/../PHPLib/controller.php";
require_once dirname(__FILE__)."/../PHPLib/producten.php";
require_once dirname(__FILE__)."/../PHPLib/contacten.php";
require_once dirname(__FILE__)."/../PHPLib/factuur.php";

class FactuurController extends Controller {

    public function index()
    {

    }

    public function action_getcontact()
    {
        if(array_key_exists ( 'BSN' , $_POST))
        {

            return Contacten::GetPersonByBSN($_POST['BSN'])->ToJson();
        }
        else
        {
            throw exception("Geen BSN opgegeven");
        }
    }
} 