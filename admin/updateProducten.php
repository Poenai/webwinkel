<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 7-7-14
 * Time: 20:45
 */
//program code
$xml = simplexml_load_file("../xml/producten.xml");

if(!empty($_POST))
{
    $xml = new SimpleXMLElement('<Producten/>');
    for($i=0;$i<count($_POST['id']);$i++)
    {
        $product = $xml->addChild("product");
        foreach($_POST as $var=>$val)
        {
            $product->addChild($var, $val[$i]);
        }
    }
    $xml->asXML("../xml/UpdatedProducten.xml");
}

//body code
?>
<!DOCTYPE html>
<html>
<head>
    <title>edit store</title>
    <!-- jquery -->
    <script src="../js/bootstrap/jquery.js"></script>

    <script type="text/javascript" src="../js/jquery/jquery-cookie.js"></script>
    <script type="text/javascript" src="../js/jquery/jquery.flexslider.js"></script>
    <script src="http://jquery-xml2json-plugin.googlecode.com/svn/trunk/jquery.xml2json.js" type="text/javascript" language="javascript"></script>
    <script type="text/javascript" src="../js/cart/shoppingcart-1-1.js"></script>
    <script type="text/javascript" src="../js/cart/querystring.js"></script>
    <script type="text/javascript" src="../js/cart/contact.js"></script>
    <script type="text/javascript" src="../js/cart/functions.js" ></script>

    <!-- json data -->
    <script src="../js/store.js" type="text/javascript"></script>

    <!-- templates -->
    <script src="../js/templates/jquery.tmpl.min.js" type="text/javascript"></script>

    <script>
        var producten = <?= json_encode($xml); ?>.product;
    </script>

    <script id="productsTemplate" type="text/x-jquery-tmpl">
		<li class="span3">
            <?php
                foreach( $xml->product[0] as $eName => $eValue)
                {
                    print "<label for=\"" .$eName. "\">" .$eName. "</label>";
                    print "<input type=\"text\" value=\"\${" .$eName. "}\" name=\"" .$eName. "[]\"/>";
                    print "<br/>";
                }
            ?>
		</li>
	</script>
</head>
<body>
<div class="row">
    <div class="span12">
        <form method="post">
            <ul id="productList" class="thumbnails product-list"></ul>
            <input type="submit"/>
        </form>
    </div>
</div>
<?php
var_dump($_POST);
?>

<script>
    $( "#productsTemplate" ).tmpl( producten )
        .appendTo( "#productList" );
</script>
</body>
</html>
