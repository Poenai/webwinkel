<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 25-7-14
 * Time: 11:24
 */

require_once dirname(__FILE__)."/contacten.php";
require_once dirname(__FILE__)."/producten.php";
require_once dirname(__FILE__)."/onderdeel.php";
require_once dirname(__FILE__)."/factuurRegel.php";

/**
 * Class Factuur
 * @description totaalbedragen zijn in int dat is betrouwbaarder en wordt gevraagdt door mollie. bedragen zijn dus in centen
 */
class Factuur
{
    /**
     * @var boolean
     */
    private $_betaalstatus = false;

    /**
     * @param boolean $betaalstatus
     */
    public function setBetaalstatus($betaalstatus)
    {
        $this->_betaalstatus = $betaalstatus;
    }

    /**
     * @return boolean
     */
    public function getBetaalstatus()
    {
        return $this->_betaalstatus;
    }

    /**
     * @var string
     */
    private $_betalingswijze;

    /**
     * @param string $betalingswijze
     */
    public function setBetalingswijze($betalingswijze)
    {
        $this->_betalingswijze = $betalingswijze;
    }

    /**
     * @return string
     */
    public function getBetalingswijze()
    {
        return $this->_betalingswijze;
    }

    /**
     * @var int
     */
    private $_factuurDatum;

    /**
     * @param int $factuurDatum
     */
    public function setFactuurDatum($factuurDatum)
    {
        $this->_factuurDatum = $factuurDatum;
    }

    /**
     * @return int
     */
    public function getFactuurDatum()
    {
        return $this->_factuurDatum;
    }

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
     * @var int
     */
    private $_id = 10;


    /**
     * @param Contact $naw
     * @param string|int $date factuurdatum
     * @param int $id alleen maar gebruiken als je weet wat je doet
     */
    public function __construct($naw = null, $date = null ,$id = null)
    {
        $this->_naw = $naw;

        if(is_null($id))
        {
            /**
             * @var $oldId int
             */
            $oldId = file_get_contents(dirname(__FILE__)."/../tmp/FactuurId.txt");
            $oldId +=1;
            fwrite(fopen(dirname(__FILE__)."/../tmp/FactuurId.txt", 'w'), $oldId);
            $this->_id = $oldId;
        }
        else
        {
            $this->_id = $id;
        }

        //regelt de factuur datum
        if(is_null($date)){
            $this->_factuurDatum = time();
        }if(is_numeric($date)){
            $this->_factuurDatum = $date;
        }else{
            $this->_factuurDatum = strtotime($date);
        }
    }

    /**
     * @return Contact
     */
    public function GetContact()
    {
        return $this->_naw;
    }

    /**
     * @return int
     */
    public function GetId()
    {
        return $this->_id;
    }

    /**
     * @param Product $product
     * @param int $aantal
     */
    public function AddProduct($product, $aantal = 1)
    {
        $this->_factuurRegels[] = new FactuurRegel($product, $aantal);
        $this->_addProductRecursivly($product, $aantal);
    }

    /**
     * @return FactuurRegel[]
     */
    public function GetAllFactuurRegels()
    {
        return $this->_realFactuurRegels;
    }

    /**
     * @return string
     */
    public function SaveAsXML()
    {
        $fXML = new SimpleXMLElement('<factuur/>');
        $fXML->addChild('id', $this->_id);
        $fXML->addChild('contact', $this->GetContact()->id);
        $fXML->addChild('factuurdatum', $this->_factuurDatum );
        $fXML->addChild('betalingswijze', $this->_betalingswijze);
        $fXML->addChild('betaalstatus', $this->_betaalstatus);
        $regelsXML = $fXML->addChild('regels');
        foreach($this->GetAllFactuurRegels() as $regel )
        {
            $regelXML = $regelsXML->addChild('regel');
            $regelXML->addChild('id', $regel->GetId());
            $regelXML->addChild('aantal', $regel->GetAantal());
        }

        $fXML->saveXML(dirname(__FILE__)."/../xml/faceturen/factuur".$this->_id.".xml");
        return dirname(__FILE__)."/../xml/faceturen/factuur".$this->_id.".xml";
    }

    /**
     * @param $identifier
     * @return Factuur
     */
    public static function GetFactuurFromXML($identifier)
    {
        if(file_exists(dirname(__FILE__)."/../xml/faceturen/factuur".$identifier.".xml"))
        {
            $xml = simplexml_load_file(dirname(__FILE__)."/../xml/faceturen/factuur".$identifier.".xml");

            //mmaak een nieuw factuur object aan.
            $f = new Factuur(Contacten::GetContactById($xml->contact), $xml->factuurdatum."", $xml->id);
            $f->_betalingswijze = $xml->betalingswijze;
            $f->_betaalstatus = $xml->betaalstatus;
            foreach($xml->regels->regel as $regel)
            {
                $f->AddProduct(Producten::GetProductByID($regel->id), $regel->aantal);
            }
            return $f;
        }
    }

    /**
     * @return Factuur[]
     */
    public static function GetAllFactuurs()
    {
        $rtw = array();
        foreach(glob(dirname(__FILE__)."/../xml/faceturen/factuur*xml") as $file)
        {
            $rtw[] = self::GetFactuurFromXML(str_replace("factuur", "", basename($file, '.xml')));
        }
        return $rtw;
    }

    /**
     * @return int
     * @description bedrag in euros
     */
    public function GetTotaalBedrag()
    {
        $som = 0;
        foreach($this->_realFactuurRegels as $regel)
        {
            $som += $regel->GetAantal() * $regel->GetProduct()->price;
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
        if(!is_null($this->_realFactuurRegels))
        {
            foreach($this->_realFactuurRegels as $regel)
            {
                if($regel->GetProduct()->id == $id)
                {
                    return $regel;
                }
            }
        }
        return null;
    }
}