<?php
require_once "PHPLib/producten.php";
?>


<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Webwinkel Drive Perfect by Poenai</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">    


<!-- jquery -->
<script src="js/bootstrap/jquery.js"></script>

<!-- cart functions -->
<script type="text/javascript" src="js/jquery/jquery-cookie.js"></script>
<script type="text/javascript" src="js/jquery/jquery.flexslider.js"></script>
<script type="text/javascript" src="js/cart/shoppingcart-1-1.js"></script>
<script type="text/javascript" src="js/cart/querystring.js"></script>
<script type="text/javascript" src="js/cart/contact.js"></script>
<script type="text/javascript" src="js/cart/functions.js" ></script>



<!-- json data -->
<script src="js/store.js" type="text/javascript"></script>
<script>
    var Catalog = <?= Producten::GetAllProducts(true) ?>;
</script>

<!-- templates -->
<script src="js/templates/jquery.tmpl.min.js" type="text/javascript"></script>



    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/cart.css" type="text/css" rel="stylesheet"/>
    <link href="css/contact.css" type="text/css" rel="stylesheet">
    <link href="css/template.css" type="text/css" rel="stylesheet">

	<script id="linkMenuTemplate" type="text/x-jquery-tmpl">
	      <li {{if current}} class="active" {{/if}}><a href="${link}">${label}</a></li>
	</script>

	<script id="brandingTemplate" type="text/x-jquery-tmpl">
		{{if logo}} 
			<a href="index.php"><img src="img/store/${logo}"></a>
		{{else}} 
			<h1><a href="index.php">${storeName}</a></h1>
		{{/if}}
	</script>


	<script id="homePageTemplate" type="text/x-jquery-tmpl">
		${homePageText}
	</script>

	<script id="mainMenuTemplate" type="text/x-jquery-tmpl">
                        <li class="">
                            <a {{if current}}class="current"{{/if}} href="${link}" class="sf-with-ul">
                                <span class="name">${label}</span>
				{{if highlight}} 
                                	<span class="desc">${highlight}</span>
				{{/if}}
                            </a>
                        </li>
	</script>

	<script id="sliderTemplate" type="text/x-jquery-tmpl">

		{{each( index, value ) slides}}
          		<div class="item {{if index==0}}active{{/if}}">
            			<img src="${image}" alt="" height="177"/>
            			<div class="carousel-caption">
              				<h2>${title}</h2>
              				<p>${text}</p>
            			</div>
          		</div>
		{{/each}}
	</script>
       

	<script id="productsTemplate" type="text/x-jquery-tmpl">
		<li class="span3">
			<div class="product-box">                                        
				<a href="product.php?product=${seoName}"><h4>${name}</h4></a>
				<a href="product.php?product=${seoName}"><img alt="" src="img/products/${image}"></a>
				<p><h3>${formatedPrice}</h3></p>
				<div class="bottom">
					<a class="view" href="product.php?product=${seoName}">Meer info</a> /
					<a class="addcart addToCart" href="#" productId="${id}">Zet in winkelwagen</a>
				</div>
			</div>
		</li>   
	</script>


	<script id="footerTemplate" type="text/x-jquery-tmpl">
				<div class="container">
				   <div class="row">
					<div class="span12 text">${footer} &nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp; Drive Perfect</div>
				   </div>
				 </div>
	</script>


	<script id="socialLinksTemplate" type="text/x-jquery-tmpl">
                        <img src="img/misc/${image}" width="26"/>&nbsp;<a href="${link}">${label}</a><br/>
	</script>

	<script id="footerBrandingTemplate" type="text/x-jquery-tmpl">
				<h4>${storeName}</h4>
				<p>
				${street}<br />
				${pc}<br />
				${address} <br />
				${website} <br />
				${email} <br />
				${mobiel} <br />
				</p>
	</script>

    <!-- analytics om de bezoeker te volgen -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-35248865-3', 'auto');
        ga('send', 'pageview');

    </script>
</head>
    





<body>		
        <div class="container">

			<div class="row" id="mainmenu">
				<div class="span7">
					<ul id="linkMenuLinks" class="nav nav-pills pull-left"/>
				</div>
				<div class="span5">
					<!-- Shopping Cart section do not edit -->
					<div class="btn-group pull-right" style="padding-top: 8px;">
            					<i class="icon-shopping-cart icon-black"></i>
            					<a id="open-cart" class="open noboxshadow dropdown-toggle" data-toggle="dropdown" href="#" style="box-shadow:none;color:FF8C00;">Mijn winkelwagen</a>
           		 			<span id="cartinfo">
                  					<span id="cartqty">(0 product(en))</span>&nbsp;<span id="cartprice">&euro;0.00</span>
            					</span>
            					<ul class="dropdown-menu minicart">
              						<li>
                  						<div id="cart-box" class="box-cart">
                  							<div class="box-content clearfix">
                  								<h3 class="lbw">Winkelwagen</h3>&nbsp;<span id="checkout-wait" style="width:15%;display:none;"><img src="img/misc/wait18trans.gif"></span><br/>
		                  						<div id="shoppingcart">
		                  						</div>
	                  						</div>
                  						</div>
              						</li>
            					</ul>
					</div>
				</div>
			</div>
            		<!-- Start Header-->
            		<div class="row show-grid">
                		<div class="span4 logo" id="branding"></div>
                		<div class="span8">

					<div class="row">
						<nav class="pull-right" id="menu">
                    					<ul class="sf-js-enabled sf-shadow" id="mainMenuLinks"></ul>
						</nav>
					</div>
                		</div>
            		</div>
            		<!-- End Header-->


	
            		<div class="row">
                		<div class="span12">

      					<!--  Carousel -->
      					<div id="main-slider" class="carousel slide home">
        					<div class="carousel-inner" id="slider">

        					</div><!-- .carousel-inner -->
        					<!--  next and previous controls here
              					href values must reference the id for this carousel -->
          					<a class="carousel-control left" href="#slider" data-slide="prev">&lsaquo;</a>
          					<a class="carousel-control right" href="#slider" data-slide="next">&rsaquo;</a>
      					</div><!-- .carousel -->
      					<!-- end carousel -->


			<br/>
			<div class="row">
				<div class="span12">
					<br/><h1 class="lbw">Veel verkochte producten..</h1><br/>

									<ul id="productList" class="thumbnails product-list"></ul>
				</div>
			</div>


			


            <footer>
                <div class="row">                   
                    <div class="span6">
						<div id="company" class="company">
						</div>
                    </div>


                    <div class="span6" id="socialLinks">

						<p>
								KvK: 58374825<br />
								BTW: NL203964081B01 <br />
								Rekening: NL47INGB0006620782
						</p>
			
                    </div>				
                </div>
		    <div id="footer-bottom"></div>
            </footer>
        </div>
</div>
</div>		



<!-- Contact Form -->
<form id="contactform" action="#" method="post">

  <h2>Neem contact op</h2>

  <ul>

    <li>
      <label for="senderName">Naam</label>
      <input type="text" name="senderName" id="senderName" placeholder="Vul uw naam in" required="required" maxlength="40" />
    </li>

    <li>
      <label for="senderEmail">Email-adres</label>
      <input type="email" name="senderEmail" id="senderEmail" placeholder="Geef uw emailadres op" required="required" maxlength="50" />
    </li>

    <li>
      <label for="message" style="padding-top: .5em;">Bericht</label>
      <textarea name="senderMessage" id="senderMessage" placeholder="Bericht..." required="required" cols="80" rows="10" maxlength="1000"></textarea>
    </li>

  </ul>

  <div id="formButtons">
    <input type="submit" id="sendMessage" name="sendMessage" value="Versturen" />
    <input type="button" id="cancel" name="cancel" value="Annuleren" />
  </div>

</form>
<div id="sendingMessage" class="statusMessage"><p>Wordt verzonden, een ogenblik</p></div>
<div id="successMessage" class="statusMessage"><p>Dank voor uw bericht, u hoort spoedig van ons</p></div>
<div id="failureMessage" class="statusMessage"><p>Er ging iets mis tijdens het versturen, probeer opnieuw</p></div>
<div id="incompleteMessage" class="statusMessage"><p>Vul alle velden in</p></div>
<div id="addToCartMessage" class="statusMessage"><p>Wordt toegevoegd aan winkelwagen</p></div>



    <!-- Le javascript
    ================================================== -->

    <!-- Bootstrap jQuery plugins compiled and minified -->
    <script src="js/bootstrap/bootstrap.min.js"></script>

    <script type="text/javascript">

    $(document).attr('title', Store.title);
    $( "#brandingTemplate" ).tmpl( Store )
		.appendTo( "#branding" );

    <!-- Bindings -->

    var linkMenuItems = getMenuItem(Links, "menu", 8, "index");
    var mainMenuItems = getMenuItem(Links, "mainmenu", 8, "index");
    var socialMenuItems = getMenuItem(Links, "social", 8, "index");


    $( "#linkMenuTemplate" ).tmpl( linkMenuItems )
	.appendTo( "#linkMenuLinks" );

    $( "#sliderTemplate" ).tmpl( Store )
	.appendTo( "#slider" );

    $( "#homePageTemplate" ).tmpl( Store )
	.appendTo( "#homeText" );

    $( "#mainMenuTemplate" ).tmpl( mainMenuItems )
	.appendTo( "#mainMenuLinks" );


    var featuredItems = getFeaturedItems(Catalog,4);

    $( "#productsTemplate" ).tmpl( featuredItems )
            .appendTo( "#productList" );





    $( "#footerTemplate" ).tmpl( Store )
	.appendTo( "#footer-bottom" );

    $( "#socialLinksTemplate" ).tmpl( socialMenuItems )
	.appendTo( "#socialLinks" );

    $( "#footerBrandingTemplate" ).tmpl( Store )
	.appendTo( "#company" );


            $(document).ready(function(){
                $('.carousel').carousel({
                    interval: 4000
                })
            });

     </script>
    
</body></html>