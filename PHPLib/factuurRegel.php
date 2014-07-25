<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 25-7-14
 * Time: 12:05
 */
require_once dirname(__FILE__)."/product.php";


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
        $this->_aantal = $aantal;
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

    public function GetProduct()
    {
        return $this->_product;
    }

} 