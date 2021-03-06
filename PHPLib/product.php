<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 24-7-14
 * Time: 14:44
 */

require_once dirname(__FILE__)."/onderdeel.php";

class Product {
    //private properties
    public $id;
    public $category;
    public $name;
    public $brand;
    public $seoName;
    public $price;
    public $availability;
    public $formatedPrice;
    public $featured;
    public $highlight;
    public $image;
    public $description;
    public $BTWpercentage;
    public $minimal;
    public $samengesteldePrijs;

    /**
     * @var Onderdeel[]
     */
    public $onderdelen;


    /**
     * @param SimpleXMLElement $Product
     */
    public function __construct($Product = null)
    {
        if(is_null($Product))
            return;

        if(property_exists($Product, 'onderdelen'))
        {
            foreach($Product->onderdelen->onderdeel as $var => $val)
            {
                $this->onderdelen[] = new Onderdeel($val->id."", $val->aantal."");
            }
        }


        foreach($Product as $peVar => $peVal)
        {
            if(property_exists('Product', $peVar) && !isset($this->$peVar))
            {
                $this->$peVar = $peVal."";
            }
        }
    }

    //wat logica voor het opslaan verwijderen en updaten van producten
    /**
     * @description is voor het opslaan van nieuwe producten en als ze al bestaan worden ze geupdated
     */
    public function Save()
    {
        Producten::SaveProduct($this);
    }

    public function Delete()
    {
        Producten::DeleteProduct($this);
    }
}