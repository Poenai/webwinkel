<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 23-7-14
 * Time: 22:41
 */
class Producten {
    //private fields
    private static  $_fileName = "";
    private static $_products;

    //public methods
    public static function Initialize()
    {
        //zorgt ervoor als de require_once uit andere mappen komt dat het path altijd juist blijft
        self::$_fileName = dirname(__FILE__)."/../xml/producten.xml";
        self::$_products = simplexml_load_file(self::$_fileName);
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
        foreach(self::$_products->product as $product)
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