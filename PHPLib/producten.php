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

    //public methods
    /**
     * @description een soort alternatiefe constructor
     */
    public static function Initialize()
    {
        //zorgt ervoor als de require_once uit andere mappen komt dat het path altijd juist blijft
        self::$_fileName = dirname(__FILE__)."/../xml/producten.xml";
        foreach(simplexml_load_file(self::$_fileName)->product as $product)
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

}

//zorg dat er een aantal dingen zijn gebeurd in de static class voordat er voor de rest dingen mee gedaan worden
Producten::Initialize();