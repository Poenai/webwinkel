<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 24-7-14
 * Time: 0:05
 */

require_once dirname(__FILE__)."/contact.php";

/**
 * Class Contacten
 */
class Contacten {
    /**
     * @var string
     */
    static private $_file;
    /**
     * @var Contact[]
     */
    static private $_contacten;


    public static function Initialize()
    {
        //zorgt ervoor als de require_once uit andere mappen komt dat het path altijd juist blijft
        self::$_file = dirname(__FILE__)."/../xml/contacts.xml";
        foreach(simplexml_load_file(self::$_file)->contact as $contact)
        {
            self::$_contacten[] = new Contact($contact);
        }

    }

    /**
     * @param int $bsn
     * @return bool
     */
    private static function _elfProef($bsn)
    {
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

    /**
     * @param int $bsn
     * @return Contact
     * @throws Exception
     */
    public static function GetPersonByBSN($bsn)
    {
        if(!self::_elfProef($bsn))
            throw new Exception("BSN is geen geldig nummer");

        foreach(self::$_contacten as $persoon)
        {
            if($persoon->BSN == $bsn)
            {
                return $persoon;
            }
        }
        throw new Exception("persoon is niet gevonden in de verzameling");
    }

    /**
     * @return Contact[]
     */
    public static function GetAllContacts()
    {
        return self::$_contacten;
    }

}

//zorg dat het xml document is ingelezen
Contacten::Initialize();