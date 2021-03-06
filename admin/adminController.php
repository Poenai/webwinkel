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
        //acties die direct uit het formulier worden gedaan
        if(isset($_POST['act']))
        {
            if($_POST['act'] == 'delete')
            {
                foreach($_POST['chk'] as $id)
                {
                    $factuur = Factuur::GetFactuurFromXML($id);
                    if(!is_null($factuur))
                    {
                        $factuur->Delete();
                    }
                }
            }
        }
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
        $this->SetVar('factuur', new Factuur());
    }

    public function savefactuur()
    {
        //deze pagina moet benaderd worden via een POST
        if(isset($_POST['invoice_date']))
        {
            try
            {
                $f = new Factuur(Contacten::GetContactById($_POST['customer_id']), $_POST['invoice_date'], $_POST['invoice_number']);
                $f->setBetalingswijze($_POST['payment_method']);
                for($i = 0; $i<count($_POST['item']);$i++)
                {
                    $f->AddProduct(Producten::GetProductByID($_POST['item'][$i]), $_POST['qty'][$i]);
                }

                $f->SaveAsXML();
                $this->SetVar('sucses', true);
            }
            catch(Exception $e)
            {
                $this->SetVar('sucses', False);
            }
        }
        else
        {
            $this->SetVar('sucses', false);
        }
    }

    /**
     * @param $id int
     */
    public function editfactuur($id = 0)
    {
        $this->SetVar('klanten', Contacten::GetAllContacts());
        $this->SetVar('producten', Producten::GetAllProducts());
        $factuur = Factuur::GetFactuurFromXML($id);
        $this->SetVar('factuur', $factuur);
        $this->SetVar('klantId', $factuur->GetContact()->id);

    }
} 