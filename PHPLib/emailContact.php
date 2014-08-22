<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 25-7-14
 * Time: 22:10
 */

class EmailContact {
    /**
     * @var string
     */
    private $address;
    /**
     * @var string
     */
    private $naam;


    /**
     * @param $address
     * @param $naam
     */
    function __construct($address, $naam)
    {
        $this->address = $address;
        $this->naam = $naam;
    }


    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }


    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $naam
     */
    public function setNaam($naam)
    {
        $this->naam = $naam;
    }

    /**
     * @return string
     */
    public function getNaam()
    {
        return $this->naam;
    }
} 