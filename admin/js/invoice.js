 var rowcount=1;



(function($) {
    $(document).ready(function() {
	 $( "#invoice_date" ).datepicker();
         $( "#invoice_date" ).datepicker("setDate", new Date());
	 
	 $("#customer_name").live('focus', function() {
            $( "#customer_name" ).autocomplete({
                source: customers
            });

	 });

	 $("#customer_name").live('blur', function() {
            $("#bill_to").val(getAddress(arr_customers,$(this).val()));
            $("#ship_to").val(getAddress(arr_customers,$(this).val()));
        });

	  $('#add-item').click(function() {
          var $tr    = $('#item_row_1');
          var $clone = $tr.clone();
          $clone.find(':text').val('');
          $clone.find('div').text('');
          $("#tablemain tr:last").after($clone);
        });


        // delete row
        $('a[name^=remove]').live('click', function () {

            if($('#tablemain tr').length>2){
            $(this).parent("td").parent("tr").remove();
            update();
            }
        });


        $('input[name^=item]').live('focus', function () {
            if ($(this).val() == "Type item code here") {
                $(this).val('');
            }
            $('input[name^=item]').autocomplete({
                source:item_codes
            });
        });

        $('input[name^=item]').live('blur', function() {

            name=getName(arr,$(this).val());
            unitval=parseFloat(getUnitValue(arr,$(this).val()));
            tax=parseFloat(getTaxValue(arr,$(this).val()));

            x= $(this).parent("td").parent("tr");
            x.find("td").each(function (j) {
              //  starting from 0
                if(j==1){
                    $(this).children().eq(0).val( name );
                }
                if(j==2){
                    $(this).children().eq(0).text( unitval );
                }
                if(j==4){
                    $(this).children().eq(0).text( tax );

                }

            });
          // alert("hello");

            update();
        
        });


        $('input[name^=item]').live('keypress', function() {       
         $('#iname_2').val( getName(arr,$(this).val()));        
        });

          // when qty change
        $('input[name^=qty]').live('blur', function() {

            update();
        });


        $("#invoice_number").live('blur', function() {
              
         $.ajax({
                type: 'GET',
                url: 'available.php?number='+$("#invoice_number").val(),
                dataType: 'html',
                success: function(html, textStatus) {
                 //alert(html);
                 if(html=='notfound'){
                       $('#incorrectcode').hide();
                       $('#correctcode').show();

                 }else{
                      $('#correctcode').hide();
                      $('#incorrectcode').show();
                 }

                },
                error: function(xhr, textStatus, errorThrown) {
                    alert('An error occurred! ' +   ( errorThrown ? errorThrown : xhr.status ));
                }
            });
        });


	});
	})(jQuery);
	
	


        function isitemsvalidated(){
            var tot=0;
            tot=$('#sub-total').text();
            tot=tot.substr(1);
            if(tot>0){
                return true;
            }else{
                return false;
            }
        }




	
