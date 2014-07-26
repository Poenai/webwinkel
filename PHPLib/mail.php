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
    private $_body;

    private $_templateLocation = "/MailTemplates/%s.template.php";
    /**
     * @var EmailContact[]
     */
    private $_to;
    /**
     * @var EmailContact[]
     */
    private $_bcc;

    //getters en setters
    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }


    public function __contsrtuct()
    {

    }

    /**
     * @param string $name
     * @param array $variablen
     * @throws Exception
     */
    public function SetTemplate($name, $variablen = array())
    {
        if(file_exists(dirname(__FILE__).sprintf($this->_templateLocation, $name)))
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
                include dirname(__FILE__).sprintf($this->_templateLocation, $name);


                $this->_body = ob_get_clean();
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
     * @param string $to
     * @param string $naam
     */
    public function AddTo($to, $naam = null)
    {
        $this->_to[] = new EmailContact($to, $naam);
    }

    /**
     * @param string $to
     * @param string $naam
     */
    public function AddBcc($to, $naam = null)
    {
        $this->_bcc[] = new EmailContact($to, $naam);
    }

    public function Send()
    {

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