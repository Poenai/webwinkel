<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 24-7-14
 * Time: 17:22
 */

class Contact {
    public $id;
    public $naam;
    public $straat;
    public $huisnummer;
    public $postcode;
    public $plaats;
    public $telefoon;
    public $BSN;
    public $email;

    /**
     * @param SimpleXMLElement $rawContact
     */
    public function __construct($rawContact = null)
    {
        //Is niet verplicht om zo te doen je kan alle properties ook zelf zetten voor als je het uit de applicatie aanspreekt
        if(is_null($rawContact))
            return;


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

    public function Save()
    {
        Contacten::SaveContact($this);
    }

    public function Delete()
    {
        Contacten::DeleteContact($this);
    }

} 