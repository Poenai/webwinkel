<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 25-7-14
 * Time: 11:24
 */

require_once dirname(__FILE__)."/contact.php";
require_once dirname(__FILE__)."/product.php";
require_once dirname(__FILE__)."/onderdeel.php";
require_once dirname(__FILE__)."/factuurRegel.php";

/**
 * Class Factuur
 * @description totaalbedragen zijn in int dat is betrouwbaarder en wordt gevraagdt door mollie. bedragen zijn dus in centen
 */
class Factuur
{
    /**
     * @var Contact
     * @description de geadreseerde van de factuur
     */
    private $_naw;
    /**
     * @var FactuurRegel[]
     * @description alles opgeteld en pakketen niet bestaand omdat ze zijn uitgesplitst
     */
    private $_realFactuurRegels;
    /**
     * @var FactuurRegel[]
     * @description de regels die een voor een worden toegevoegd zonder dat pakketen worden uitgesplitst en producten bij elkaar worden opgeteld
     */
    private $_factuurRegels;


    /**
     * @param Contact $naw
     */
    public function __construct($naw)
    {
        $this->_naw = $naw;
    }

    /**
     * @param Product $product
     * @param int $aantal
     */
    public function AddProduct($product, $aantal = 1)
    {
        $this->_factuurRegels = new FactuurRegel($product, $aantal);
        $this->_addProductRecursivly($product, $aantal);
    }

    /**
     *
     */
    public function SaveAsXML()
    {
        //TODO: er moet gekozen onder welke naam en welke gegevens daar allemaal in komen te staan
    }

    /**
     * @param $identifier
     * @return Factuur
     */
    public static function GetFactuurFromXML($identifier)
    {
        //TODO implementatie aan de hand van de vorige functie
    }

    /**
     * @return int
     * @description bedrag in centen
     */
    public function GetTotaalBedrag()
    {
        $som = 0;
        foreach($this->_realFactuurRegels as $regel)
        {
            $som += intval( $regel->GetProduct()->aantal * $regel->GetProduct()->price * 100);
        }
        return $som;
    }


    /**
     * @param Product $product
     * @param int $aantal
     * @description medthode is recursive opgezet zodat in theorie de pakken bijna oneindig zouden kunnen nesten.
     */
    private function _addProductRecursivly($product, $aantal)
    {
        if($product->category == "pakket")
        {
            foreach($product->onderdelen as $onderdeel)
            {
                $this->_addProductRecursivly(Producten::GetProductByID($onderdeel->id), $onderdeel->aantal * $aantal);
            }
        }
        else
        {
            $regel = $this->_factuurRegelById($product->id);
            if(!is_null($regel))
            {
                $regel->AddAantal($aantal);
            }
            else
            {
                $this->_realFactuurRegels[] = new FactuurRegel($product, $aantal);
            }
        }
    }

    /**
     * @param int $id
     * @return FactuurRegel|null
     */
    private function _factuurRegelById($id)
    {
        foreach($this->_realFactuurRegels as $regel)
        {
            if($regel->GetProduct()->id == $id)
            {
                return $regel;
            }
        }
        return null;
    }



} 