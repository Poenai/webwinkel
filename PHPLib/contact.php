<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 24-7-14
 * Time: 17:22
 */

class Contact {
    public $naam;
    public $straat;
    public $huisnummer;
    public $postcode;
    public $plaas;
    public $telefoon;
    public $BSN;
    public $email;

    /**
     * @param SimpleXMLElement $rawContact
     */
    public function __construct($rawContact)
    {
        foreach($rawContact as $pname => $pvalue)
        {
            if(property_exists('Contact', $pname))
            {
                $this->$pname = $pvalue."";
            }
        }
    }

    /**
     * @return string
     */
    public function ToJson()
    {
        return json_encode($this);
    }

    public function IsValid()
    {
        //TODO: kijken of alle gegevens van het goede type zijn
    }
} 