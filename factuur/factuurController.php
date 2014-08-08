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
            throw new exception("Geen BSN opgegeven");
        }
    }

    public function action_betaal()
    {

        $f = new Factuur(Contacten::GetContactById($_POST['person']));
        foreach($_POST["Products"] as $id => $aantal)
        {
            $f->AddProduct(Producten::GetProductByID(str_replace("ID", "", $id)), $aantal);
        }


        //$f->SaveAsXML();

        return json_encode(array('url'=>$f->SetBetaalObject()));
    }
} 