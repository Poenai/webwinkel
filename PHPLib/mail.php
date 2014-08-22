<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 25-7-14
 * Time: 16:20
 */

require_once dirname(__FILE__)."/PHPMailer/PHPMailerAutoLoad.php";

/**
 * Class Mail
 */
class Mail {

    /**
     * @var string
     */
    static private $_templateLocation = "/MailTemplates/%s.template.php";

    /**
     * @param string $name
     * @param array $variablen
     * @return string html boddy
     * @throws Exception
     */
    static public function GetEmailBOdyFromTemplate($name, $variablen = array())
    {
        if(file_exists(dirname(__FILE__).sprintf(self::$_templateLocation, $name)))
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
                include dirname(__FILE__).sprintf(self::$_templateLocation, $name);


                return ob_get_clean();
            }catch (Exception $e){
                //voorkom dat er hierna nog steeds niks op het scherm komt als het mis gaat
                ob_get_clean();
                throw $e;
            }
        }
        else
        {
            throw new Exception("template not found");
        }
    }

    /**
     * @return PHPMailer
     */
    static public function  GetMailerAsSMTP()
    {
        $mail = new PHPMailer(true);
        include dirname(__FILE__)."/config/mail.config.php";

        $mail->isSMTP();
        $mail->Host = $config['SMTP']['Host'];
        $mail->SMTPAuth = $config['SMTP']['SMTPAuth'];
        $mail->Username = $config['SMTP']['Username'];
        $mail->Password = $config['SMTP']['Password'];
        $mail->SMTPSecure = $config['SMTP']['SMTPSecure'];

        $mail->From = $config['SMTP']['From'];
        $mail->FromName = $config['SMTP']['FromName'];

        $mail->setWordWrap($config['SMTP']['WordWrap']);

        return $mail;


        //$config['SMTP']
    }
}

//testen

$t = new Mail();
$t->SetTemplate("factuur", array('barld' => 'is geweldig'));

var_dump($t);