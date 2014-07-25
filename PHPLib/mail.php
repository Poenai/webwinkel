<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 25-7-14
 * Time: 16:20
 */

/**
 * Class Mail
 */
class Mail {

    /**
     * @var string
     */
    private $_body;

    public function __contsrtuct()
    {

    }

    /**
     * @param string $name
     * @param array $variablen
     */
    public function SetTemplate($name, $variablen = array())
    {
        if(file_exists(dirname(__FILE__)."/MailTemplates/".$name.".template.php"))
        {
            try{
                //zorg dat de inhoud niet wordt weergegeven op het scherm
                ob_start();

                //alle indexen van de van array beschikbaar maken als variable
                foreach($variablen as $index => $value)
                {
                    $$index = $value;
                }

                //laat de template uitvoeren
                include dirname(__FILE__)."/MailTemplates/".$name.".template.php";


                $this->_body = ob_get_clean();
            }catch (Exception $e){
                //voorkom dat er hierna nog steeds niks op het scherm komt als het mis gaat
                ob_get_clean();
                throw $e;
            }
        }
    }
}

//testen

$t = new Mail();
$t->SetTemplate("factuur", array('barld' => 'is geweldig'));

var_dump($t);