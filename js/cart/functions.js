/**
*Will retreive featured items from the catalog
*
**/
function getFeaturedItems(catalog, maxFeaturedItems) {
	var countItems = 0;
	var countFeaturedItems = 0;
	var featuredItems = $.grep(catalog, function(item, i) { 
		countItems ++;
		item.last='';//reset last flag
		if(countFeaturedItems < maxFeaturedItems) {
			if(item.featured!=null && item.featured=='yes') {
				countFeaturedItems ++;
				if(countFeaturedItems==maxFeaturedItems) {
					item.last = "last";
				}
				return item;
			}
		} 
	}); 
	return featuredItems;
}
/**
*Will retreive a category of products from the catalog
*
**/
function getCategory(catalog, categoryName, maxItemsPerLine, start, maxItemsPerPage) {
	var totalCount = 0;
	var countItems = 0;
	var lineCounter = 0;
	var pageCounter = 0;
	var startCounter = false;

	var categoryItems = $.grep(catalog, function(item, i) {
		item.last='';

		if(item!=null && item.category && item.category==categoryName) {
			totalCount++;



		if(!startCounter) {
			countItems ++;
		}

		if(countItems==start && pageCounter<maxItemsPerPage) {
			startCounter = true;
			if(item!=null && item.category && item.category==categoryName) {
				lineCounter ++;
				pageCounter ++;
				if(lineCounter==maxItemsPerLine) {
          				item.last = "last";
					lineCounter = 0;
				}
				return item;
			}
		}

		}
	});

	var o = new Object();
	o.totalCount = totalCount;
	o.pageCount = pageCounter;
	o.products = categoryItems;

	//var json = '{"totalCount":'+ totalCount + ',"pageCount":'+ pageCounter + ',"products":[' +  categoryItems + ']}';
	//alert(json);
 
	return o;
}
/**
*Returns the name of the page less the extension
*
**/
function getPage(path) {
	var sPath = path;
	var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
	sPage = stripExtension(sPage);
	return sPage;
}

function stripExtension(page) {
	var dotpos = page.indexOf(".");
	if (dotpos > -1) {
		page= page.substring(0, dotpos);
	}
	return page;
}

/**
*Returns menu items based on their type
*
**/
function getMenuItem(menus, type, maxItemsPerLine, page) {
	var counter = 0;
	var menuItems = $.grep(menus, function(item, i) { 
	if(item!=null && item.type && item.type==type) {
		counter ++;
		if(counter==maxItemsPerLine){
			item.last="last";
			counter = 0;
		}
		if(item.link) {
			var sPage = stripExtension(item.link);
			if(sPage == page) {
				item.current="yes";
			}
		}
		return item;
	}
	}); 
	return menuItems;
}

/**
*Returns a menu item node
*
**/
function getMenuItemNode(menus, name) {

	var menuItem = $.grep(menus, function(item, i) { 
	   if(item!=null && item.name && item.name==name) {
		return item;		
	   }
	}); 
	return menuItem;
}

function getProduct(catalog, productName) {


	var product = $.grep(catalog, function(item, i) { 
		if(item!=null && item.seoName && item.seoName==productName) {
			return item;
		}
	}); 
	return product[0];
}

function getProductById(catalog, productId) {
	var product = $.grep(catalog, function(item, i) {
		if(item!=null && item.id && item.id==productId) {
			return item;
		}
	}); 
	//first found
	return product[0];
}


function getShipping(store, code) {
	var shipping = $.grep(store, function(item, i) { 
		if(item!=null && item.code && item.code==code) {
			return item;
		}
	}); 
	return shipping[0];
}

function addProductToCart(catalog, productId, quantity, property) {
	var product = getProductById(catalog, productId);
	if(product==null) {
		alert('Product id ' + productId + ' does not exist');
	}
	var updateQty = true;
	if(product) {
		if(product.updateQty && product.updateQty=='no') {
			updateQty = false;
			quantity = 1;
		}
		//$.cookie('sku',null, { expires: cookieDurationDays ,path: '/'});
		addToCart(product.id,quantity,product.name,product.price,property, updateQty);

	}
}

/**
*
* Returns a query parameter value '?parameterName=' by passing the name of the parameter 'parameterName'
**/

$.urlParam = function(name){
    	var results = new RegExp('[\\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);
	if(results==null) {
		return "";
	}
    	return results[1] || 0;

}

