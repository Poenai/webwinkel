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

    /**
     * @return boolean
     */
    public function IsValid()
    {
        return $this->_elfProef() && filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @return bool
     */
    private function _elfProef()
    {
        $bsn = $this->BSN;
        $value = 0;
        if(strlen($bsn) == 9)
        {
            for($i = 0;$i<8;$i++)
            {
                $value += $bsn[$i] * (9-$i);
            }
            //laatste getal moet *-1
            $value += $bsn[8] * -1;
        }
        return $value%11==0;
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