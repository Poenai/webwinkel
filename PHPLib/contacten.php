<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 24-7-14
 * Time: 0:05
 */

/**
 * Class Contacten
 */
class Contacten {
    /**
     * @var string
     */
    static private $_file;
    /**
     * @var SimpleXMLElement
     */
    static private $_contacten;


    public static function Initialize()
    {
         //zorgt ervoor als de require_once uit andere mappen komt dat het path altijd juist blijft
         self::$_file = dirname(__FILE__)."/../xml/contacts.xml";
        self::$_contacten = simplexml_load_file(self::$_file);
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
     * @param bool $JsonString
     * @return string | SimpleXMLElement
     * @throws Exception
     */
    public  static function GetPersonByBSN($bsn, $JsonString)
    {
        if(!self::_elfProef($bsn))
            throw new Exception("BSN is geen geldig nummer");

        foreach(self::$_contacten->contact as $persoon)
        {
            if($persoon->BSN == $bsn)
            {
                if(!$JsonString)
                {
                    return $persoon;
                }
                else
                {
                    return json_encode($persoon);
                }
            }
        }
        throw new Exception("persoon si niet gevonden in de verzameling");
    }

}

//zorg dat het xml document is ingelezen
Contacten::Initialize();