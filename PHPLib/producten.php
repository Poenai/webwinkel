<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 23-7-14
 * Time: 22:41
 */
require_once dirname(__FILE__)."/product.php";

class Producten {
    //private fields
    private static  $_fileName = "";
    /**
     * @var Product[]
     */
    private static $_products;

    /**
     * @var SimpleXMLElement
     */
    private static $_xml;

    //public methods
    /**
     * @description een soort alternatiefe constructor
     */
    public static function Initialize()
    {
        //zorgt ervoor als de require_once uit andere mappen komt dat het path altijd juist blijft
        self::$_fileName = dirname(__FILE__)."/../xml/producten.xml";
        $xml = simplexml_load_file(self::$_fileName);
        self::$_xml = $xml;
        foreach($xml->product as $product)
        {
            self::$_products[] = new Product($product);
        }
    }

    public static function GetAllProducts($JsonString = false)
    {
        if($JsonString)
            return json_encode(self::$_products);
        else
            return self::$_products;
    }

    public static function GetProductByID($ID, $JsonString = false)
    {
        foreach(self::$_products as $product)
        {
            if($product->id == $ID)
            {
                if($JsonString)
                    return json_encode($product);
                else
                    return $product;
            }
        }
        throw new Exception("product not found");
    }

    /**
     * @param Product $product
     */
    public static function SaveProduct($product)
    {
        $xmlP = null;
        //kijk of het product al in de xml staat.
        foreach(self::$_xml->product as $p)
        {
            if($product->id == $p->id)
            {
                $xmlP = $p;
                break;
            }
        }

        if(is_null($xmlP))
        {
            $xmlP = self::$_xml->addChild("product");
        }

        if(!is_null($xmlP))
        {
            foreach($product as $var => $productAtribuur)
            {
                if($var == "onderdelen")
                {
                    //heeft een speciale behandeling nodig omdat het een array is
                    //hij word unset om vervolgens weer aan te maken
                    unset($xmlP->onderdelen->onderdeel);
                    foreach($productAtribuur as $onderdeel)
                    {
                        $xmlO = $xmlP->onderdelen->addChild("onderdeel");
                        $xmlO->addChild("id", $onderdeel->id);
                        $xmlO->addChild("aantal", $onderdeel->aantal);
                    }
                }
                else
                {
                    $xmlP->{$var} = $productAtribuur;
                }
            }
        }

        self::$_xml->saveXML(self::$_fileName);
    }

    /**
     * @param Product $product
     */
    public static function DeleteProduct($product)
    {
        foreach(self::$_xml as $xproduct)
        {
            if($xproduct->id == $product->id)
            {
                var_dump($xproduct);
                unset($xproduct[0]);
                unset($product);


                var_dump(self::$_xml );

                self::$_xml->saveXML(self::$_fileName);

                break;
            }
        }
    }

}

//zorg dat er een aantal dingen zijn gebeurd in de static class voordat er voor de rest dingen mee gedaan worden
Producten::Initialize();