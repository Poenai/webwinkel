<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 26-7-14
 * Time: 17:02
 */

require_once "../PHPMailer/PHPMailerAUtoload.php";

$mail = new PHPMailer();

//$mail->

$default = 'SMTP';

$config =
    array
    (
        'SMTP' => array(
            'Host' => '',
            'SMTPAuth' => true,
            'Username' => '',
            'Password' => '',
            'SMTPSecure ' => '',

            'From' => '',
            'FromName' => '',

            'WordWrap' => 50,
            

        )
    );