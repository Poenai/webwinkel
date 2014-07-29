function getName(arr,code){
	 for (i=0;i<=arr.length;i++){
	   if(code==arr[i][0]){
	    return arr[i][1];
	   }
	 }
	}

	function getDes(arr,code){
	 for (i=0;i<=arr.length;i++){
	   if(code==arr[i][0]){
	    return arr[i][2];
	   }
	 }
	}

	function getUnitValue(arr,code){
	 for (i=0;i<=arr.length;i++){
	   if(code==arr[i][0]){
	    return arr[i][3];
	   }
	 }
	}

	function getTaxValue(arr,code){
	 for (i=0;i<=arr.length;i++){
	   if(code==arr[i][0]){
	    return arr[i][4];
	   }
	 }
	}

	function getTaxType(arr,code){
	 for (i=0;i<=arr.length;i++){
	   if(code==arr[i][0]){
	    return arr[i][5];
	   }
	 }
	}

	function getAddress(arr,code){
	for (i=0;i<=arr.length;i++){
	   if(code==arr[i][0]){
	    return arr[i][2];
	   }
	 }
	}	











function update(){
    var sub_total=0;
    var tax_total=0;
    $("#tablemain").find("tr").each(function (i) {
        var unit_val = 0;
        var qty = 0;
        var tax = 0;
        var code="";
        $(this).css("background-color", "#efefef");
        $(this).find("td").each(function (j) {


            if (j == 0) {
                code = ( $(this).children().eq(0).val());



            }

            if (j == 2) {
                unit_val = parseFloat( $(this).children().eq(0).text());


            }
            if (j == 3) {
                qty = parseFloat( $(this).children().eq(0).val());
                // update tax column

            }
            if (j == 4) {
              //  tax = $(this).children().eq(0).text();
                tax=parseFloat(getTaxValue(arr,code))*qty;
                $(this).children().eq(0).text(tax);
                tax_total=tax_total+ tax ;
                //alert(tax);

            }
            if (j == 5) {
                line_total = unit_val * qty;

              //  sub_total = sub_total + rate * hours;
                $(this).children().eq(0).text(line_total.toFixed(2));
                sub_total=sub_total+line_total;
            }

        });

    });

    $("#sub-total").text(curr_symbol+sub_total.toFixed(2));
    $("#tax-total").text(curr_symbol+  parseFloat(tax_total).toFixed(2));
    $("#total").text(curr_symbol+(parseFloat(sub_total)+parseFloat(tax_total)).toFixed(2));
}