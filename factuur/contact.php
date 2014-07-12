<?php /*<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Contacten</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/jquery.xml2json.js"></script>
    </head>
	<body>
        <div id='mainContent'>

        </div>
	</body>
	<script>
        function setContact(naam, straat, huisnummer, postcode, plaats, telefoon) {
            $('#contactNaam').html(naam);
            $('#contactAdres').html(straat + ' ' + huisnummer);
            $('#contactPlaats').html(postcode + ' ' + plaats);
            $('#contactTelefoon').html(telefoon);

        }

        $.get('xml/contacts.xml', function(xml){
		var contacts = $.xml2json(xml);

            $.each(contact.contact, function(i, item) {
                $("#mainContent").append('<div class="panel panel-default"><div class="panel-body" id="contactInfo" onclick="setContact(this.naam + this.straat + this.huisnummer + this.postcode + this.plaats + this.telefoon)" style="padding:5px">'+this.naam+'<br/>'+this.straat+' '+this.huisnummer+'<br/>'+this.postcode+' '+this.plaats+'<br/>'+this.telefoon+'</div></div>');
            });
	});
    </script>
</html>*/?>
<?php
function ElfProef($getal)
{
    $value = 0;
    if(strlen($getal) == 9)
    {
        for($i = 0;$i<8;$i++)
        {
            $value += $getal[$i] * (9-$i);
        }
        //laatste getal moet *-1
        $value += $getal[8] * -1;
    }
    return $value%11==0;
}

function GetPerson()
{
    $personen = simplexml_load_file("../xml/contacts.xml");
    foreach($personen->contact as $persoon)
    {
        if($persoon->BSN == $_POST["BSN"])
        {
            return $persoon;
        }
    }
    return null;
}



if(array_key_exists ( 'BSN' , $_POST) && ElfProef($_POST['BSN']))
{
    $persoon = getPerson();
    if($persoon!=null)
    {
        print json_encode($persoon);
    }
    else
        http_response_code(204);

}
else
    http_response_code(204);