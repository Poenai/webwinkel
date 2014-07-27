<?php
require_once "../PHPLib/contacten.php";

try
{
    if(array_key_exists ( 'BSN' , $_POST))
    {

        print Contacten::GetPersonByBSN($_POST['BSN'])->ToJson();
    }
    else
    {
        http_response_code(204);
    }
}
catch (Exception $e)
{
    http_response_code(204);
}