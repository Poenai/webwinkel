<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 25-7-14
 * Time: 12:05
 */
require_once dirname(__FILE__)."/product.php";


/**
 * Class FactuurRegel
 */
class FactuurRegel {
    /**
     * @var Product
     */
    private $_product;
    /**
     * @var int
     */
    private $_aantal;

    /**
     * @param Product $product
     * @param int $aantal
     */
    public function __construct($product, $aantal)
    {
        $this->_product = $product;
        $this->_aantal = intval( $aantal);
    }

    /**
     * @param int $aantal
     */
    public function AddAantal($aantal)
    {
        $this->_aantal+=$aantal;
    }

    /**
     * @param int $aantal
     */
    public function SetAantal($aantal)
    {
        $this->_aantal = $aantal;
    }

    /**
     * @return int
     */
    public function GetAantal()
    {
        return $this->_aantal;
    }

    /**
     * @return Product
     */
    public function GetProduct()
    {
        return $this->_product;
    }

    //regelDetails Die een algemeen resultaat geeft over de hele regel zoals ze ook op de factuur staan
    /**
     * @return int
     */
    public function GetId()
    {
        return $this->_product->id;
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return $this->_product->name;
    }

    /**
     * @return float
     */
    public function GetBruto()
    {
        return $this->_product->price / 1.21;
    }

    /**
     * @return float
     */
    public function GetNetto()
    {
        return $this->_product->price;
    }

    /**
     * @return int
     */
    public function GetBTW()
    {
        return $this->_product->BTW;
    }

    /**
     * @return float
     */
    public function GetBedragIncl()
    {
        return $this->_product->price * $this->_aantal;
    }

    /**
     * @return float
     */
    public function GetBedragExcl()
    {
        return ($this->_product->price * $this->_aantal) /1.21 ;
    }



} 