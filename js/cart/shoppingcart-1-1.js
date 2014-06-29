/**
* jQuery Mini Shopping Cart
*
* Copyright (c) 2010 Carl Samson (www.shopizer.com)
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource.org/licenses/mit-license.php
* http://www.gnu.org/licenses/gpl.html
*
* This script is totally html / javascript
* no programming required
* configure the block of properties
* small shopping cart image is <sku_number>_cart.jpg
* merchant requires a paypal account
*/		

		
		


		var mode="embedded";//standard | embedded
		var env = "production";//sandbox | production

		var labelNoItem = 'No items in your cart';
		var labelItems = 'items';
		var labelItem = 'item';
		var labelCheckout = 'Checkout';
		var labelPreparingCheckout = 'Preparing ...';
		var labelTotal = 'Total : ';




		//default values
		var currency = '$';
		var currencyCode = 'CAD';			
		
		


		//do not edit
		var decimal ="."
		var cookieDurationDays = 10;//number of days in cookie
		var useShoppingCartImage = false;
		var checkoutAdditionalCostMessage = '';//if additional cost is applied, this line must not be null or empty
		var checkoutAdditionalCost = 0;//required, should be 0 or more
		var variableAdditionalCost = 'handling_cart';//can be tax_cart or handling_cart
		
		var payPalUser='';
		var embeddedPayUrl='https://sandbox.paypal.com/webapps/adaptivepayment/flow/pay?paykey=';
		var payPalUrl='https://sandbox.paypal.com:443/webscr';
		if(env=='production') {
			payPalUrl='https://www.paypal.com/cgi-bin/webscr';
			embeddedPayUrl='https://www.paypal.com/webapps/adaptivepayment/flow/pay?paykey=';
		}

		var pcancel;
		var preturn;
		var ipn;



		var win;
		var thankYouPage = '';
		
		var NO_ITEMS = '<H3>' + labelNoItem + '</H3>';
		

		$(document).ready(function(){


				


				//completion code
				var completedFlag = $.getQueryString('checkoutCompleted'); 
				if(completedFlag ) {
					if(completedFlag =='true') {
						emptyCart();
					}
				}

				//TODO en_us from store
				//data.error


				//will delete cookie when page load
				//$.cookie('sku',null, { expires: cookieDurationDays ,path: '/'});


				//override default values with store values
				if(Store.currency) {
					currency = Store.currency;
				}
				if(Store.currencyCode) {
					currencyCode = Store.currencyCode;
				}				
				if(Store.decimal) {
					decimal = Store.decimal;
				}
				if(Store.checkoutAdditionalCostMessage) {
					checkoutAdditionalCostMessage = Store.checkoutAdditionalCostMessage;
				}
				if(Store.checkoutAdditionalCost) {
					checkoutAdditionalCost = Store.checkoutAdditionalCost;
				}
				if(Store.payPalUser) {
					payPalUser=Store.payPalUser;
				}
				if(Store.baseUrl) {
					//thankYouPage = Store.baseUrl + thankYouUrl;
				}
				if(Store.useShoppingCartImage) {
					useShoppingCartImage = Store.useShoppingCartImage;
				}



				//cart variables
				


				addBindings();
				fillCart();

				pcancel = encodeURI(Store.baseUrl + "/payment/" + mode + "-" + "cancel.html");
			  	preturn = encodeURI(Store.baseUrl + "/payment/" + mode + "-" + "success.html");
				ipn = encodeURI(Store.baseUrl + "/payment/standard-ipn.php");
				
				


	}); 


	function fadeInContent() {
		if($('#addToCartMessage')) {
			$('#container').fadeTo( 'slow', .2 );
			$('#addToCartMessage').fadeIn();
		}
	}


	function fadeOutContent() {
		if($('#addToCartMessage')) {
			$('#addToCartMessage').fadeOut();
			$('#container').fadeTo( 'slow', 1 );
		}
	}
			       



	 function fillCart() {
	 	
	 	
	 	var CART_PREPENDER='<table class="table" style="margin-bottom: 5px"><tbody>';
	 	var CART_APPENDER='</tbody></table>';

		var products = $.cookie( 'sku' );

		if(products!=null && products!='') {
			var productsArray = products.split("|");
			var globalproducts = CART_PREPENDER;
			if(productsArray.length>1) {
				for ( var i = 0; i < productsArray .length; i++ )  {
					var skuQty = productsArray[i];
					var productLine = printProduct(skuQty,1);
					if(productLine==null) {
						continue;
					}
					globalproducts = globalproducts + productLine + '';
				}
			} else {
				var skuQty = productsArray[0];
				var productLine = printProduct(skuQty,1);
				if(productLine!=null) {
					globalproducts = globalproducts + productLine + '';
				}
			}
			
			globalproducts = globalproducts + CART_APPENDER;

			//comment  //$('html').on('click.dropdown.data-api', clearMenus) in bootstrap-dropdown
			
			
			


				
				
			var TOTALS_HEADER= '';
			

			var TOTALS_ADDITIONAL_PREPENDER= '';
			var TOTALS_ADDITIONAL_APPENDER ='';
			
			var TOTAL_ADDITIONAL_LINE_LABEL_PREPENDER='<div class="row" style="padding-right:4px;"><div class="pull-right">';
			var TOTAL_ADDITIONAL_LINE_LABEL_APPENDER=' :';
			var TOTAL_ADDITIONAL_LINE_AMOUNT_PREPENDER=' ';
			var TOTAL_ADDITIONAL_LINE_AMOUNT_APPENDER='</div></div>';
			
			var GRAND_TOTAL_LABEL_PREPENDER = '<div class="total-box"><div class="pull-right"><font class="total-box-label">';
			var GRAND_TOTAL_LABEL_APPENDER = '';
			var GRAND_TOTAL_AMOUNT_PREPENDER = '<font class="total-box-price">';
			var GRAND_TOTAL_AMOUNT_APPENDER = '</font></font></div></div>';

				
			if(globalproducts!='') {
				
				//do not edit
				var checkout_total= '<strong><span id="checkout-total"></span></strong>';
				var checkout_all_total= '<strong><span id="checkout-total-plus"></span></strong>';


				//sub-total
				var total = 'TOTALS_HEADER';

				var totalLine = GRAND_TOTAL_LABEL_PREPENDER + labelTotal + GRAND_TOTAL_LABEL_APPENDER + GRAND_TOTAL_AMOUNT_PREPENDER + checkout_total + GRAND_TOTAL_AMOUNT_APPENDER;

				var additionalCosts = '';
				if(checkoutAdditionalCostMessage!=null && checkoutAdditionalCostMessage!='') {
					additionalCosts = TOTALS_ADDITIONAL_PREPENDER;
					var additionalCostsLine = TOTAL_ADDITIONAL_LINE_LABEL_PREPENDER + checkoutAdditionalCostMessage + TOTAL_ADDITIONAL_LINE_LABEL_APPENDER;
					additionalCosts = additionalCosts + additionalCostsLine;
					if(checkoutAdditionalCost > 0) {
						var additionalCostsLinePrice = TOTAL_ADDITIONAL_LINE_AMOUNT_PREPENDER + currency + round_decimals(checkoutAdditionalCost,2) + TOTAL_ADDITIONAL_LINE_AMOUNT_APPENDER;
						additionalCosts = additionalCosts + additionalCostsLinePrice;
					} 
					additionalCosts = additionalCosts + TOTALS_ADDITIONAL_APPENDER;
					if(checkoutAdditionalCost > 0) {
						totalLine = GRAND_TOTAL_LABEL_PREPENDER + labelTotal + GRAND_TOTAL_LABEL_APPENDER + GRAND_TOTAL_AMOUNT_PREPENDER + checkout_all_total + GRAND_TOTAL_AMOUNT_APPENDER;
					}
				}

				globalproducts = globalproducts + additionalCosts + totalLine;


				var FOOTER_HEADER= '';
				var MESSAGE_BLOCK = '<span id="message">&nbsp;</span>';
				var WAIT_BLOCK = '';
				var PP_MESSAGE_BLOCK = '<img src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif" border="0" width="110" alt="Checkout with paypal">';
				var PP_BUTTON_BLOCK = '<a href="javascript:invokePayPal();" id="paypal-checkout"><img border="0" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="right"></a>';


				//globalproducts = globalproducts + FOOTER_HEADER;
				
				var FOOTER = FOOTER_HEADER + '<table class="table-nr" style="margin-bottom: 0px"><tbody><tr style="background-color: transparent;"><td colspan="2" style="border-top: none;">' + MESSAGE_BLOCK + '</td></tr>';
				FOOTER = FOOTER + '<tr style="background-color: transparent"><td style="border-top: none;">' + PP_MESSAGE_BLOCK + '</td><td style="border-top: none;"><div class="pull-right">' + PP_BUTTON_BLOCK + '</div></td></tr>';

				FOOTER = FOOTER + '</tbody></table>';
				

				//globalproducts = globalproducts + '<div><img src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif" border="0" width="110" alt="Checkout with paypal"><a href="javascript:invokePayPal();" id="paypal-checkout"><img border="0" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="right"></a></div>';

				//globalproducts = globalproducts + '<div><span id="message" style="float:left;width:85%;">&nbsp;</span><span id="checkout-wait" style="float:right;text-align:right;width:15%;display:none;"><img src="img/misc/wait18trans.gif"></span><br/></div>';

				globalproducts = globalproducts + FOOTER;

				
				$("#shoppingcart").html(globalproducts);

				addCartBindings();



				if(checkoutAdditionalCost > 0) {
					printPriceQty(3);
				} else {
					printPriceQty(2);
				}


			} else {//garbage, delete cookie
				//delete cookie
				products = null;
				$.cookie('sku',null, { expires: cookieDurationDays ,path: '/'});
			}
		}


		printPriceQty(1);
		if(products==null || products=='') {
			$("#shoppingcart").html(NO_ITEMS);

		}
	}


	function invokePayPal() {
		if(win) {
			win.close();

		}

		//set purchased products in a cookie


		var otherCost = checkoutAdditionalCost;


		var total = parseFloat(calculatePriceQty());

		if($('#shipping')) {
			var shippingCode = $('#shipping').val();
			if(Store.shipping) {
				var shipping = getShipping(Store.shipping,shippingCode);
				if(shipping!=null) {
					otherCost = shipping.cost;
				}
			}
		}




		if(otherCost>0) {
			total = total + parseFloat(round_decimals(otherCost,2));
		}
			
		total = round_decimals(total,2);
		if($("#checkout-wait")) {
			$("#checkout-wait").show();
		}

		url = "payment/standard-api.php";

		//check mode
		if(mode=='embedded') {
			url = "payment/embedded-api.php";

		} 


		var pdata = "payPalUser=" + payPalUser + "&pcancel=" + pcancel + "&preturn=" + preturn;
		
		var productsArray = getCartProducts();
		//var products = '&products=';
		var productsJSON = '[';
		var files = null;
		var count = 0;
		for(i=0; i<productsArray.length; i++) {

			var name = productsArray[i].product.name;


			if(productsArray[i].property) {
				name = name + ' - (' + productsArray[i].property + ')';
			}


			name = encodeURIComponent(name); 			

			productsJSON = productsJSON + '{"name":"' + name + '","id":"' + productsArray[i].product.id + '","qty":"' + productsArray[i].qty + '","itemPrice":"' + productsArray[i].product.price + '"';
   			var price = parseFloat(round_decimals(productsArray[i].qty * productsArray[i].product.price,2));
			productsJSON = productsJSON + ',"price":"' + price +'"';
			if(productsArray[i].product.filename) {
				productsJSON = productsJSON + ',"file":"' + productsArray[i].product.filename +'"';
			}
			productsJSON = productsJSON + "}";
			count ++;
			
			if(count<productsArray.length) {
				productsJSON = productsJSON + ',';
			}
		}
		productsJSON = productsJSON + ']';

		var dataStart = '{';
		var dataEnd = '}';
		var data = '"currency":"' + currencyCode + '","total":"' + total + '"';

		if(otherCost > 0) {				
			data = data + ',"variableAdditionalCost":"' + variableAdditionalCost +'","additionalCost":"' + round_decimals(otherCost,2) + '"';
		}


		if(Store.askshipping && Store.askshipping==true) {
			data = data + ',"askshipping":"true"';
		}

		if($('#memo')) {
			var memo = encodeURIComponent($('#memo').val()); 
			data = data + ',"memo":"' + memo + '"';
		}

		var data = data + ',"products":' + productsJSON;

		var jsonData = dataStart + data + dataEnd;

		//alert(jsonData);
		//alert(pdata);

		//alert(url + '?' + pdata + '&data=' + jsonData);

		$.ajax({
        		type: "POST",
        		url: url + '?' + pdata + '&data=' + jsonData,

			
        		//data: jsonData,
        		dataType: "json",
        		success: function(data) {

			     $("#checkout-wait").hide();
            		     if(data.error){
                		  $("#message").html("<font color='red'>" + data.error + " Try again.</font>");
				  alert(data.error);
				  if($("#checkout-wait")) {
				 	$("#checkout-wait").hide();
				  }
            		     } else {
				    //$("div#cart-box").slideUp("slow");
				    $("#toggle-cart a").toggle();
				    if(data) {
				      $.cookie('payKey',data.payKey, { expires: 1 ,path: '/'});
   				      if(mode=='embedded') {
					launchEmbedded(data.payKey);
				      } else {
					buildPayPalForm(data.payKey);
				      }

				     if($("#checkout-wait")) {
				 	$("#checkout-wait").hide();
				     }
				  }
            		     }
        	       },
		       error: function() {
				 if($("#checkout-wait")) {
				 	$("#checkout-wait").hide();
				 }
				 $("#message").html("<font color='red'>An error occurred while accessing " + url + " try again.</font>");

		       }
       		});  

	}


	function addBindings() {

			//$("#checkout-wait").css("display", "none");

			$(".addToCart").click(function(){ 
					fadeInContent();

					var sku = $(this).attr("productId");
					var qty = '#quantity-productId-'+ sku;
					var prop = '#property-productId-'+ sku;
					var quantity = $(qty).val();
					var property=null;
					if(prop) {
						property = $(prop).val();
					}
					if(!quantity || quantity==null || quantity==0) {
						quantity = 1;
					}
					addProductToCart(Catalog, sku, quantity,property);
					fillCart();
					setTimeout(fadeOutContent,2000);
			});

			$(".pay").click(function(){ 
					fadeInContent();

					var sku = $(this).attr("productId");
					var qty = '#quantity-productId-'+ sku;
					var prop = '#property-productId-'+ sku;
					var quantity = $(qty).val();
					var property=null;
					if(prop) {
						property = $(prop).val();
					}
					if(!quantity || quantity==null || quantity==0) {
						quantity = 1;
					}
					addProductToCart(Catalog, sku, quantity,property);
					setTimeout(fadeOutContent,2000);
			});


	}


	function addCartBindings() {

			$('.removeProductIcon').click(function() {//REMOVE PRODUCT
            		var id = $(this).attr('productid');
				//remove from cookie
				var obj = $('#'+id);
				obj.remove();
				removeItem(id);
				fillCart();
    			});

			$('#checkout').click(function() {//CHECKOUT
				showCheckout();

    			});

	}


	function getCartProducts() {

		var products = $.cookie( 'sku' );
		var cartProducts = new Array();
		var count = 0;
		if(products!=null) {
			var productsArray = products.split("|");
			if(productsArray.length>1) {
				for ( var i = 0; i < productsArray .length; i++ )  {
					var skuQty = productsArray[i];
					var productDetails = skuQty.split("&");
					var cartProduct = new Object();
					for ( var j = 0; j < productDetails.length; j++ )  {

					    if(j==0) {//sku	
						sku = productDetails[j];
				
						//get product entity from sku
						var product = getProductById(Catalog,sku);
						if(product) {//delete from cookie
							cartProduct.product = product;
						} 
					    }

					    if(j==1) {//qty
				                qty = productDetails[j];
					        cartProduct.qty=qty;
					    }

					    if(j==2) {//property
				            	property = productDetails[j];
					    	cartProduct.property=property;
					    }
	
					}
					
					   
					cartProducts[count] = cartProduct;
					count ++;

				}					
			}  else {
				var skuQty = productsArray[0];
				var productDetails = skuQty.split("&");
				var cartProduct = new Object();
				for ( var j = 0; j < productDetails.length; j++ )  {


					if(j==0) {//sku	
						sku = productDetails[j];
				
						//get product entity from sku
						var product = getProductById(Catalog,sku);
						if(product) {//delete from cookie
							cartProduct.product = product;
						} 
					}

					if(j==1) {//qty
				            qty = productDetails[j];
					    cartProduct.qty=qty;
					}

					if(j==2) {//property
				            property = productDetails[j];
					    cartProduct.property=property;
					}
				}

				cartProducts[count] = cartProduct;
				count ++;
			}
		}
		return cartProducts;
	}


	function buildPayPalForm(payKey) {

		var products = $.cookie( 'sku' );
		if(products!=null) {
			var productsArray = products.split("|");
			var form = '<form id="payPalForm" method="post" action="' + payPalUrl + '"><input type="hidden" name="cmd" value="_cart"><input type="hidden" name="upload" value="1"><input type="hidden" name="business" value="' +payPalUser+ '">';
			if(productsArray.length>1) {
				var count = 0;
				for ( var i = 0; i < productsArray .length; i++ )  {
					var skuQty = productsArray[i];
					var productLine = printProductPayPal(skuQty,count);
					if(productLine==null) {
						continue;
					}
					count++;
					form = form + productLine;
				}
			} else {
				var skuQty = productsArray[0];
				var productLine = printProductPayPal(skuQty,0);
				//alert(productLine);
				if(productLine==null) {

					productLine='Nothing to display';
				}
				form = form + productLine;
			}
			if(checkoutAdditionalCost > 0) {				
				form = form + '<input type="hidden" name="' + variableAdditionalCost +'" value="' + round_decimals(checkoutAdditionalCost,2)+'">';
			}
			form = form + '<input type="hidden" name="bn" value="Shopizer_Cart_WPS">';
			form = form + '<input type="hidden" name="currency_code" value="' + currencyCode + '"><input type="hidden" name="custom" value="' + $.cookie('payKey') +'" ><input type="hidden" name="cancel_return" value="' + pcancel + '"><input type="hidden" name="return" value="' + preturn +'"><input type="hidden" name="notify_url" value="' + ipn + '"></form>';

			alert(form);
			$("#payment-form").html(form);
			$("#payPalForm").submit(); 
		}
	}

			

	function printProduct(skuQty,mode) {
		var productDetails = skuQty.split("&");
		var line = '';
		var qtyLine = '';
		var nameLine = '';
		var priceLine = '';
		var sku = '';
		var total = 0;
		var price = 0;
		var name = '';
		var qty = 1;
		for ( var j = 0; j < productDetails.length; j++ )  {
			if(j==0) {//sku	
				sku = productDetails[j];
				
				//get product entity from sku
				var product = getProductById(Catalog,sku);
				if(!product) {//delete from cookie
					removeItem(sku);
					return null;
				} 
				name = product.name;
				price = product.price;
			}
			if(j==1) {//qty
				if(productDetails[j]=='0'){
					return null;
				}
				qty = productDetails[j];
			}					
		}
		
		var GLOBAL_PREPEND= '';
		var CART_PREPEND_ID = '<tr class="cart-product" id="';
		var CART_APPEND_ID = '">';
		
		var CART_PREPEND_QTY = '<td><strong>';
		var CART_APPEND_QTY = '</strong></td>';
		
		var CART_PREPEND_PRICE = '<td>';
		var CART_APPEND_PRICE = '</td>';
		
		var CART_PREPEND_IMAGE = '<td><img width="40" height="40" src="img/products/';
		var CART_APPEND_IMAGE = '"></td>';
		
		var CART_PREPEND_NAME = '<td>';
		var CART_APPEND_NAME = '</td>';
		
		var CART_PREPEND_REMOVE = '<td><button class="close removeProductIcon" productid="';
		var CART_APPEND_REMOVE = '">x</button></tr>';
		var GLOBAL_APPEND= '';


		
		line = CART_PREPEND_ID + sku + CART_APPEND_ID;
		if(mode==2) {
			qtyLine  = CART_PREPEND_QTY + qty + CART_APPEND_QTY;
		} else {
			qtyLine  = qty
		}
		nameLine = name;
		priceLine = CART_PREPEND_PRICE + currency + price + CART_APPEND_PRICE;
		if(mode==2) {
			priceLine = CART_PREPEND_PRICE + currency + qty * price + CART_APPEND_PRICE;
		}

		var image = '';
		if(useShoppingCartImage && product.image) {
			image = CART_PREPEND_IMAGE + product.image + CART_APPEND_IMAGE;
		}
		
		var productNameLine = CART_PREPEND_NAME + qtyLine + ' ' + nameLine + CART_APPEND_NAME;
		
		var removeLine = CART_PREPEND_REMOVE + sku + CART_APPEND_REMOVE;	

		if(mode==1) {
			line = GLOBAL_PREPEND + line +  image + productNameLine + priceLine + removeLine + GLOBAL_APPEND;	
		} else if(mode==2) {
			line = line + qtyLine + '<div class="col2">' + nameLine + '</div>' + '<div class="col3">' + priceLine + '</div></div><div class="clear-float"></div>';	
		}  

		return line;
	}

	function printProductPayPal(skuQty, index) {
		var productDetails = skuQty.split("&");
		index = index + 1;
		var sku = '';
		var total = 0;
		var price = 0;
		var qty = 1;
		var name = '';
		for ( var j = 0; j < productDetails.length; j++ )  {
			if(productDetails[j]=='') {
				return null;
			}
			if(j==0) {//sku	

				sku = productDetails[j];

				//get product entity from sku
				var product = getProductById(Catalog,sku);
				if(!product) {//delete from cookie
					removeItem(sku);
					return null;
				} 
				name = product.name;
				price = product.price;

			}
			if(j==1) {//qty
				qty = productDetails[j];
			}					
		}

		var skuLine = '<input type="hidden" name="item_number_' + index + '" value="' + sku + '">';
		var qtyLine  = '<input type="hidden" name="quantity_' + index + '" value="' +  qty + '">';
		var nameLine = '<input type="hidden" name="item_name_' + index + '" value="' + name + '">';
		var priceLine = '<input type="hidden" name="amount_' + index + '" value="' + price + '">';

		line = skuLine + qtyLine +  nameLine + priceLine;	
	
		return line;
	}

	function printPriceQty(mode) {
		var products = $.cookie( 'sku' );
		var total=0;
		var qty = 0;
		var lItem = labelItem;
		if(products!=null && products!='') {
			var productsArray = products.split("|");
			if(productsArray.length>1) {
				for ( var i = 0; i < productsArray.length; i++ )  {
					var skuQty = productsArray[i];
					var price = getPrice(skuQty);
					if(price==null) {
						continue;
					}
					total = total + parseFloat(price);
					qty = qty + getQuantity(skuQty);
				}
			} else {
				var skuQty = productsArray[0];
				var price = getPrice(skuQty);
				if(price==null) {
					return;
				}
				total = total + parseFloat(price);
				qty = qty + getQuantity(skuQty);
			}
		}
		if(qty>1) {
			lItem = labelItems;
		}

		if(mode==1) {//summary
			$("#cartprice").html(currency + round_decimals(total,2));
			$("#cartqty").html('(' + qty + ' ' + lItem + ')');
		} else if(mode==2) {//checkout
			$("#checkout-total").html(currency + round_decimals(total,2));
		} else if(mode==3) {
			total = total + parseFloat(checkoutAdditionalCost);
			$("#checkout-total-plus").html(currency + round_decimals(total,2));
		}
	}

	function calculatePriceQty() {
		var products = $.cookie( 'sku' );
		var total=0;
		var qty = 0;
		var lItem = labelItem;
		if(products!=null && products!='') {
			var productsArray = products.split("|");
			if(productsArray.length>1) {
				for ( var i = 0; i < productsArray.length; i++ )  {
					var skuQty = productsArray[i];
					var price = getPrice(skuQty);
					if(price==null) {
						continue;
					}
					total = total + parseFloat(price);
					qty = qty + getQuantity(skuQty);
				}
			} else {
				var skuQty = productsArray[0];
				var price = getPrice(skuQty);
				if(price==null) {
					return;
				}
				total = total + parseFloat(price);
				qty = qty + getQuantity(skuQty);
			}
		}
		if(qty>1) {
			lItem = labelItems;
		}


		return round_decimals(total,2);


	}

	function getPrice(skuQty) {
		var productDetails = skuQty.split("&");
		var qty = 1;
		var product = null;
		for ( var j = 0; j < productDetails.length; j++ )  {			
			if(j==0) {//sku	
				sku = productDetails[j];
				
				//get product entity from sku
				product = getProductById(Catalog,sku);
				if(!product) {
					return null;
				}
			} 
			if(j==1) {//qty
				qty = parseInt(productDetails[j]);
			}
				
		}

		return parseFloat(qty * product.price);
	}

	function getQuantity(skuQty) {
		var productDetails = skuQty.split("&");
		var qty = 1;
		for ( var j = 0; j < productDetails.length; j++ )  {
			if(j==1) {//qty
				qty = parseInt(productDetails[j]);
			}	
		}
		return qty;
	}



	function round_decimals(original_number, decimals) { 
		var result1 = original_number * Math.pow(10, decimals) ;
		var result2 = Math.round(result1) ;
		var result3 = result2 / Math.pow(10, decimals);
		return pad_with_zeros(result3, decimals) ;
	} 

	function pad_with_zeros(rounded_value, decimal_places) { 
		// Convert the number to a string
		 var value_string = rounded_value.toString() 
		// Locate the decimal point 
		var decimal_location = value_string.indexOf(decimal) 

		// Is there a decimal point? 
		if (decimal_location == -1) { 
			// If no, then all decimal places will be padded with 0s 
			decimal_part_length = 0
			 // If decimal_places is greater than zero, tack on a decimal point 
			value_string += decimal_places > 0 ? decimal : "" 
		} else { 
			// If yes, then only the extra decimal places will be padded with 0s 
			decimal_part_length = value_string.length - decimal_location - 1 
		} 
		// Calculate the number of decimal places that need to be padded with 0s 
		var pad_total = decimal_places - decimal_part_length 
		if (pad_total > 0) { 
			// Pad the string with 0s 
			for (var counter = 1; counter <= pad_total; counter++) 
				value_string += "0" 
		} 
		return value_string 
	} 




	function removeItem(sku) {
		var products = $.cookie( 'sku' );
		if(products!=null) {
			var productsArray = products.split("|");
			var resultLine = '';
			if(productsArray.length>0) {
				for ( var i = 0; i < productsArray.length; i++ )  {
					var line = productsArray[i];
					var productDetails = line.split("&");
					var s = '';
					var q = 0;
					var n = 0;
					var p = 0;
					for ( var j = 0; j < productDetails.length; j++ )  {
						if(j==0) {	
							s = productDetails[j];
						}
						if(j==1) {
							q = productDetails[j];
						}	
					}
					if(s!=sku) {
						if(resultLine=='') {
							//resultLine = s + '&' +	q + '&' + n + '&' + p;
							resultLine = s + '&' +	q;
						} else {
							//resultLine = resultLine + '|' + s + '&' +	q + '&' + n + '&' + p;
							resultLine = resultLine + '|' + s + '&' +	q;
						}
					}
				}
			}
			$.cookie('sku',resultLine, { expires: cookieDurationDays,path: '/' });
		}
		fillCart();
	}

	function addToCart(sku, qty, name, price, property, updateQty) {

		//alert('add to cart ' + sku + ' qty ' + qty + ' name ' + name + ' price ' + ' property ' + property + ' updateQty ' + updateQty);

		var products = $.cookie( 'sku' );
		if(products!=null) {
			var productsArray = products.split("|");
			var resultLine = '';
			var found = false;
			if(productsArray.length>0) {
				for ( var i = 0; i < productsArray.length; i++ )  {
					var line = productsArray[i];
					var productDetails = line.split("&");
					var s = '';
					var q = 0;
					var n = 0;
					var p = 0;
					var product = null;
					for ( var j = 0; j < productDetails.length; j++ )  {
						if(j==0) {	
							s = productDetails[j];
							product = getProductById(Catalog,sku);
							if(!product) {
								return null;
							}

						}
						if(j==1) {
							q = productDetails[j];
						}			
					}
					if(s==sku) {


						if(product) {
							if(product.filename || !updateQty) {//no more than 1 digital
								return;
							}
						}


						q = parseInt(q) + 1;
						found = true;
					}
					if(i==0) {
						//resultLine = s + '&' + q + '&' + n + '&' + p;
						resultLine = s + '&' +	q;
						if(property && property!=null) {
							resultLine = resultLine + '&' + property;
						}
					} else {
						//resultLine = resultLine + '|' + s + '&' +	q + '&' + n + '&' + p;
						resultLine = resultLine + '|' + s + '&' +q;
						if(property && property!=null) {
							resultLine = resultLine + '&' + property;
						}
					}
				}
				if(!found) {
					var product = getProductById(Catalog,sku);
					if(!product) {
						return null;
					}
					if(product.filename) {
						qty = 1;
					}

					resultLine = resultLine + '|' + sku + '&' + qty;
					if(property && property!=null) {
						resultLine = resultLine + '&' + property;
					}
				}
			}
			$.cookie('sku',resultLine, { expires: cookieDurationDays ,path: '/'});
		} else {
			var resultLine = sku + '&' + qty;
			if(property && property!=null) {
				resultLine = resultLine + '&' + property;
			}
			$.cookie('sku',resultLine, { expires: cookieDurationDays ,path: '/'});
		}
		
	}


        function closeAdaptiveFlowWindow(){
                try {flow_Form.closeFlow();} catch(e) {}
                try {flow_Javascript.closeFlow();} catch(e) {}
        }




	function emptyCart() {
		$.cookie('sku',null, { expires: cookieDurationDays ,path: '/'});
		$.cookie('payKey',null, { expires: cookieDurationDays ,path: '/'});
	}
