<?php
$xml = simplexml_load_file("../xml/producten.xml");


?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Factuur</title>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/daterangepicker.css" />
        <link rel="stylesheet" href="css/keyboard.css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script src="https://jquery-ui.googlecode.com/svn-history/r3982/trunk/ui/i18n/jquery.ui.datepicker-nl.js"></script>
        <script src="js/moment.min.js"></script>
        <script src="js/jquery.daterangepicker.js"></script>
        <script src="js/jquery.keyboard.min.js"></script>
        <script src="js/jquery.keyboard.extension-all.min.js"></script>

    </head>
	<body>
		<header>
			<h1>Factuur</h1>
			<address contenteditable id="dialogBtn">
                <p id="bklik">Klik hier om uw adresgegevens op te halen</p>
				<p id="contactNaam"></p>
				<p id="contactAdres"></p>
                <p id="contactPlaats"></p>
				<p id="contactTelefoon"></p>
			</address>
            <div id="dialog">
                <h2>Voer uw BSN gegevens in om uw adresgegevens op te halen</h2>
                <br/>
                <form action="" onsubmit="GetAdressGegevens(); return false;">
                    <label for="BSN">BSN:</label>
                    <input type="text" id="BSN" name="BSN" />
                    <input type="submit" />
                </form>
            </div>
			<span></span>
		</header>
		<article>
			<h1>Recipient</h1>
			<address>
				<p>Drive Perfect<br />V. Poenai</p>
			</address>
			<table class="meta">
				<tr>
					<th><span>Factuur #</span></th>
					<td><span contenteditable>101138</span></td>
				</tr>
				<tr>
					<th><span>Datum</span></th>
					<td><span contenteditable><input size="45" type="text" id="datepicker" /></span></td>
				</tr>
				<tr>
					<th><span>Periode</span></th>
					<td><span contenteditable><input size="45" type="text" id="daterangepicker" /></span></td>
				</tr>
				<tr>
					<th><span>Betaalmethode</span></th>
					<td><span contenteditable>Overmaking via bank</td>
				</tr>
				<tr>
					<th><span>Vervaldatum</span></th>
					<td><span contenteditable></td>
				</tr>
			</table>
			<table class="inventory">
				<thead>
					<tr>
						<th style="width:40px;"><span>ID</span></th>
						<th><span>Product</span></th>
						<th style="width:60px;"><span>Tarief Bruto</span></th>
						<th style="width:50px;"><span>Aantal</span></th>
						<th style="width:60px;"><span>Tarief Netto</span></th>
						<th style="width:50px;"><span>BTW %</span></th>
						<th style="width:75px;"><span>Bedrag incl. BTW</span></th>
						<th style="width:75px;"><span>Bedrag excl. BTW</span></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>

			<table class="balance">
				<tr>
					<th><span>Subtotaal bedrag incl 21% BTW</span></th>
					<td><span data-prefix>&euro;</span><span id="totaalBtwHoog">0.00</span> [BTW &euro;<span id="totaalBtw">0.00</span>]</td>
				</tr>
				<tr>
					<th><span>Subtotaal bedrag BTW verlegd</span></th>
					<td><span data-prefix>&euro;</span><span id="totaalBtwLaag">0.00</span></td>
				</tr>
				<tr class='factuurbedrag'>
					<th><span>Factuurbedrag</span></th>
					<td><span data-prefix>&euro;</span><span id="factuurBedrag">0.00</span></td>
				</tr>
			</table>
		</article>
		<aside>
			<h1><span></span></h1>
			<div contenteditable>
				<p>Wij verzoeken u vriendelijk om het factuurbedrag binnen 14 dagen na factuurdatum over te maken op rekeningnummer &lt;NL41 RABO 0123 4567 89&gt;</p>
			</div>
		</aside>
	</body>
	<script>
    var info = new Array;
	var d = new Date();
	var month = new Array();
	month[0] = "Januari";
	month[1] = "Februari";
	month[2] = "Maart";
	month[3] = "April";
	month[4] = "Mei";
	month[5] = "Juni";
	month[6] = "Juli";
	month[7] = "Augustus";
	month[8] = "September";
	month[9] = "Oktober";
	month[10] = "November";
	month[11] = "December";
	var n = d.getUTCDate() + " " + month[d.getUTCMonth()] + " " + d.getUTCFullYear();
	$('#datepicker').val(n);

    function setContact(naam, straat, huisnummer, postcode, plaats, telefoon) {
        $('#contactNaam').html(naam);
        $('#contactAdres').html(straat + ' ' + huisnummer);
        $('#contactPlaats').html(postcode + ' ' + plaats);
        $('#contactTelefoon').html(telefoon);

    }

	function roundDecimal(input){
		return (Math.round(input * 100) / 100).toFixed(2);
	}

    function calcTotal(){
        var totaalBruto = 0.00;
        var totaalNetto = 0.00;
        var totaalBtwHoog = 0.00;
        var totaalBtwLaag = 0.00;
        $.each(window.info,function(i, item) {
            if (i >= 0) {
                if(this['BTWpercentage'] == 21)
                {
                    totaalBtwHoog += parseFloat(this['SubBruto']);
                    totaalNetto += parseFloat(this['SubNetto']);
                }
                else {
                    totaalBtwLaag += parseFloat(this['SubBruto']);
                }
                totaalBruto += parseFloat(this['SubBruto']);
            }
        });

        var totaalBtw = totaalBtwHoog - totaalNetto;

        $('#totaalBtwHoog').html(totaalBtwHoog.toFixed(2));
        $('#totaalBtwLaag').html(totaalBtwLaag.toFixed(2));
        $('#totaalBtw').html(totaalBtw.toFixed(2));
        $('#factuurBedrag').html(totaalBruto.toFixed(2));
    }

	function calcPrice(id, counter){
		var aantal = $("#aantal"+id).val();
		var bruto = $("#bruto"+id).html();
		var netto = $("#netto"+id).html();
        var subaantal = $("#aantal"+id+"\\.1");
        if(subaantal.length) {
            subaantal.val(aantal);
            var subid = id+'\\.1';
            var subbruto = $("#bruto"+subid).html();
            var subnetto = $("#netto"+subid).html();
            var subbrutototaal = roundDecimal(parseFloat(subbruto) * parseFloat(aantal));
            var subnettototaal = roundDecimal(parseFloat(subnetto) * parseFloat(aantal));

            if(aantal != ""){
                var subcounter = counter + 1;
                $('#brutototaal'+subid).html(subbrutototaal);
                $('#nettototaal'+subid).html(subnettototaal);
                window.info[subcounter]['SubBruto'] = subbrutototaal;
                window.info[subcounter]['SubNetto'] = subnettototaal;

                calcTotal();
            }
        }

		var brutototaal = roundDecimal(parseFloat(bruto) * parseFloat(aantal));
		var nettototaal = roundDecimal(parseFloat(netto) * parseFloat(aantal));

		if(aantal != ""){
			$('#brutototaal'+id).html(brutototaal);
			$('#nettototaal'+id).html(nettototaal);

            window.info[counter]['SubBruto'] = brutototaal;
            window.info[counter]['SubNetto'] = nettototaal;
            calcTotal();
        }
	}

	function fillZero(id, counter) {
		if($('#aantal'+id).val() == ""){
			$('#aantal'+id).val('0');
			$('#brutototaal'+id).html('0.00');
			$('#nettototaal'+id).html('0.00');

            window.info[counter]['SubBruto'] = 0.00;
            window.info[counter]['SubNetto'] = 0.00;
            calcTotal();
		}
        else if($('#aantal'+id).val() == "00"){
            $('#row'+id).hide();
            $('#row'+id+'\\.1').hide();
        }
	}

    var $_GET = {};

    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
        function decode(s) {
            return decodeURIComponent(s.split("+").join(" "));
        }

        $_GET[decode(arguments[1])] = decode(arguments[2]);
    });


    var producten = <?= json_encode($xml); ?>;

    var counter = 0;
    //get is leeg dus plaats maar alle artikelen
    if(jQuery.isEmptyObject($_GET)) {
        $.each(producten.product, function(i, item) {
            var id = this.id;
            var disabled = '';
            if(id.toString().indexOf('.') !== -1){
                var disabled = 'disabled';
                $(".inventory > tbody:last").append('<tr id="row'+id+'"><td><span>'+this.name+'</span></td><td style="text-align:right;"><span data-prefix>&euro;</span><span id="bruto'+id+'">'+roundDecimal(parseFloat(this.price))+'</span></td><td style=display:none;><input type="text" class="virtualKeyboard" style="margin-top:-2px;width:50px;text-align:center;" maxlength="2" id="aantal'+id+'" onfocusout="fillZero('+id+','+counter+')" onchange="this.value=this.value.replace(/[^0-9]+/g, \'\');calcPrice('+id+','+counter+')" value="0" '+disabled+' /></span></td><td><span data-prefix>&euro;</span><span id="netto'+id+'">'+(roundDecimal((parseFloat(this.price) / (100 + parseInt(this.BTWpercentage))) * 100))+'</span></td><td style="text-align:left;"><span>'+this.BTWpercentage+'%</span></td><td><span data-prefix>&euro;</span><span id="brutototaal'+id+'">0.00</span></td><td><span data-prefix>&euro;</span><span id="nettototaal'+id+'">0.00</span></td></tr>');
                var idParent = id.toString().replace('.1','');
                $("#td-id-"+idParent).attr("rowspan","2");
                $("#td-id-"+idParent).attr("style","vertical-align:middle;text-align:center;");
                $("#td-aantal-"+idParent).attr("rowspan","2");
                $("#td-aantal-"+idParent).attr("style","vertical-align:middle;text-align:center;");
            }
            else {
                $(".inventory > tbody:last").append('<tr id="row'+id+'"><td id="td-id-'+id+'" style="text-align:center;">'+id+'</td><td><span>'+this.name+'</span></td><td><span data-prefix>&euro;</span><span id="bruto'+id+'">'+roundDecimal(parseFloat(this.price))+'</span></td><td id="td-aantal-'+id+'"><input type="text" class="virtualKeyboard" style="margin-top:-2px;width:50px;text-align:center;" maxlength="2" id="aantal'+id+'" onfocusout="fillZero('+id+','+counter+')" onchange="this.value=this.value.replace(/[^0-9]+/g, \'\');calcPrice('+id+','+counter+')" value="0" '+disabled+' /></span></td><td><span data-prefix>&euro;</span><span id="netto'+id+'">'+(roundDecimal((parseFloat(this.price) / (100 + parseInt(this.BTWpercentage))) * 100))+'</span></td><td><span>'+this.BTWpercentage+'%</span></td><td><span data-prefix>&euro;</span><span id="brutototaal'+id+'">0.00</span></td><td><span data-prefix>&euro;</span><span id="nettototaal'+id+'">0.00</span></td></tr>');
            }
            window.info[counter] = [];
            window.info[counter]['Naam'] = this.name;
            window.info[counter]['Brutotarief'] = parseFloat(this.price);
            window.info[counter]['BTWpercentage'] = this.BTWpercentage;
            window.info[counter]['SubBruto'] = 0.00;
            window.info[counter]['SubNetto'] = 0.00;
            counter++;
        });
    }
    else {
        $.each(producten.product, function(i, item) {
            var id = this.id;
            var disabled = 'disabled';
            var idtemp = id.replace('.1','');
            var idclean = id.replace('.1','\\.1')
            if(typeof $_GET['ID'+idtemp] != 'undefined')
            {
                if(this.category && this.category == 'pakket' && this.onderdelen)
                {
                    //als er maar een artikel in het pakket zit is het direct bereikbaar
                    if(this.onderdelen.onderdeel.id)
                    {
                        $_GET['ID'+this.onderdelen.onderdeel.id] = $_GET['ID'+this.onderdelen.onderdeel.id] || 0;
                        $_GET['ID'+this.onderdelen.onderdeel.id] += this.onderdelen.onderdeel.aantal * $_GET['ID'+idtemp];
                    }
                    else
                    {
                        $.each(this.onderdelen.onderdeel, function(j, Item){
                            //check of er wel iets inzit
                            $_GET['ID'+this.id] = $_GET['ID'+this.id] || 0;
                            $_GET['ID'+this.id] += this.aantal * $_GET['ID'+idtemp];
                        });
                    }
                }

                else if(id.toString().indexOf('.') !== -1){
                    $(".inventory > tbody:last").append('<tr id="row'+id+'"></td><td><span>'+this.name+'</span></td><td style="text-align:right;"><span data-prefix>&euro;</span><span id="bruto'+id+'">'+roundDecimal(parseFloat(this.price))+'</span></td><td style="display:none"><input type="text" class="virtualKeyboard" style="margin-top:-2px;width:50px;text-align:center;" maxlength="2" id="aantal'+id+'" onfocusout="fillZero('+id+','+counter+')" onkeyup="this.value=this.value.replace(/[^0-9]+/g, \'\');calcPrice('+id+','+counter+')" value="'+$_GET['ID'+idtemp]+'" '+disabled+' /></span></td><td><span data-prefix>&euro;</span><span id="netto'+id+'">'+(roundDecimal((parseFloat(this.price) / (100 + parseInt(this.BTWpercentage))) * 100))+'</span></td><td style="text-align:left;"><span>'+this.BTWpercentage+'%</span></td><td><span data-prefix>&euro;</span><span id="brutototaal'+id+'">0.00</span></td><td><span data-prefix>&euro;</span><span id="nettototaal'+id+'">0.00</span></td></tr>');
                    $("#td-id-"+idtemp).attr("rowspan","2");
                    $("#td-id-"+idtemp).attr("style","vertical-align:middle;text-align:center;");
                    $("#td-aantal-"+idtemp).attr("rowspan","2");
                    $("#td-aantal-"+idtemp).attr("style","vertical-align:middle;text-align:center;");
                }
                else {
                    $(".inventory > tbody:last").append('<tr id="row'+id+'"><td id="td-id-'+id+'"><span>'+id+'</span></td><td><span>'+this.name+'</span></td><td><span data-prefix>&euro;</span><span id="bruto'+id+'">'+roundDecimal(parseFloat(this.price))+'</span></td><td id="td-aantal-'+id+'"><input type="text" class="virtualKeyboard" style="margin-top:-2px;width:50px;text-align:center;" maxlength="2" id="aantal'+id+'" onfocusout="fillZero('+id+','+counter+')" onkeyup="this.value=this.value.replace(/[^0-9]+/g, \'\');calcPrice('+id+','+counter+')" value="'+$_GET['ID'+idtemp]+'" '+disabled+' /></span></td><td><span data-prefix>&euro;</span><span id="netto'+id+'">'+(roundDecimal((parseFloat(this.price) / (100 + parseInt(this.BTWpercentage))) * 100))+'</span></td><td><span>'+this.BTWpercentage+'%</span></td><td><span data-prefix>&euro;</span><span id="brutototaal'+id+'">0.00</span></td><td><span data-prefix>&euro;</span><span id="nettototaal'+id+'">0.00</span></td></tr>');
                }


                window.info[counter] = [];
                window.info[counter]['Naam'] = this.name;
                window.info[counter]['Brutotarief'] = parseFloat(this.price);
                window.info[counter]['BTWpercentage'] = this.BTWpercentage;
                window.info[counter]['SubBruto'] = 0.00;
                window.info[counter]['SubNetto'] = 0.00;

                calcPrice(idclean, counter);
                counter++;
            }
        });
    }
    $('.virtualKeyboard').keyboard({
        layout: 'custom',
        customLayout: {
            'default' : [
                '7 8 9',
                '4 5 6',
                '1 2 3',
                '{c} 0 {a}',
                '{bksp}'
            ]
        },
        maxLength : 6,
        restrictInput : true, // Prevent keys not in the displayed keyboard from being typed in
        useCombos : false // don't want A+E to become a ligature
    }).addTyping();

    $(document).ready(function() {

        $('#daterangepicker').dateRangePicker({
            format: 'DD MMMM YYYY',
            language: 'nl',
            separator: ' tot '
        });

        $( "#datepicker" ).datepicker({
            dateFormat: 'dd MM yy',
            beforeShow: function(input, inst)
            {
                inst.dpDiv.css({marginTop: '8px', marginLeft: '-300px'});
            }
        });

        $('.virtualKeyboard').keyboard({
            layout : 'num',
            restrictInput : true, // Prevent keys not in the displayed keyboard from being typed in
            preventPaste : true,  // prevent ctrl-v and right click
            autoAccept : true
        }).addTyping();
    });

    $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        height: 600,
        width:335,
        open: function(ev, ui){
            $('#myIframe').attr('src','contact.php');
        }
    });

    $('#dialogBtn').click(function(){
        $('#dialog').dialog('open');
    });

    //vang het BSN nummer op
    function GetAdressGegevens(form)
    {
        $.post( "contact.php", { BSN: $("#BSN").val()},
            function( data, status ) {
                //verzoek moet met sucses worden voltooid en mag niet leeg wezen
                if(status != "success")
                {
                    alert("invoer was verkeert");
                    return;
                }
                $("#contactNaam").text(data.naam);
                $("#contactAdres").text(data.straat + " " + data.huisnummer);
                $("#contactPlaats").text(data.postcode + " " + data.plaats);
                $("#contactTelefoon").text(data.telefoon);

                $('#dialog').dialog('close');
                $('#bklik').remove();

            }, "json")
            .fail(function()
            {
                alert("invoer was verkeert");
            }
            );
    }

    </script>

    <style>
    </style>
</html>