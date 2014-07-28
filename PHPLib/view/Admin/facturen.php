<div id="sub">

    <ul>
        <li><a href="../customer/view-customers.php" class="active">Toon facturen</a></li>
        <li><a href="../customer/add-customer.php">Nieuwe factuur</a></li>
    </ul>
</div>
</div>

<div id="container">
    <div id="content"  style="padding-top:25px ">
        <script type="text/javascript">
            $(function () {

                $('#delete').tooltip('hide');
                $('#pay').tooltip('hide');
                $('#pdf').tooltip('hide');
                $('#email').tooltip('hide');

                $('.btn').click(function (e) {

                    if ($("input:checked").length > 0) {

                        if ($(this).attr('id') == "delete") {
                            if (jQuery(':checkbox').is(':selected')) {
                                alert("text");
                            }
                            ;
                            $('#act').val("delete");
                            $('#thisform').submit();
                        }
                        if ($(this).attr('id') == "pay") {
                            if ($("input:checked").length > 1) {
                                alert("Please select only one row");
                                return
                            }
                            $('#act').val("pay");
                            $('#thisform').attr('action', '../payment/add_payment_specific.php');
                            $('#thisform').submit();
                        }
                        if ($(this).attr('id') == "pdf") {
                            $('#act').val("pdf");
                            $('#thisform').attr('action', 'invoice_in_pdf.php');
                            $('#thisform').submit();
                        }
                        if ($(this).attr('id') == "email") {
                            if ($("input:checked").length > 1) {
                                alert("Please select only one row");
                                return
                            }
                            $('#act').val("email");
                            $('#thisform').attr('action', '');
                            $('#thisform').submit();
                        }
                        return;
                    }else{
                        alert("Please Select a row");
                    }
                });


                $(':checkbox').click(function (e) {
                    /*   if($("input:checked").length>0){
                     $('#delete').show();
                     $('#pay').show();
                     $('#pdf').show();
                     $('#email').show();

                     }else{
                     $('#delete').hide();
                     $('#pay').hide();
                     $('#pdf').hide();
                     $('#email').hide();
                     } */

                });
            });
        </script>
        <div id="viewcustomer">




            <form action="" method="post" id="thisform">
                <input type="hidden" id="act" name="act"/>

                <div id="toolbar">
                    <div class="btn-group">
                        <button type="button" id="delete" style="margin-right:15px;" data-toggle="tooltip" title="delete invoice" class="btn">Verwijder</button>
                        <button type="button" id="pay" class="btn" data-toggle="tooltip" title="add payment to invoice">Betaling</button>
                        <button type="button" id="pdf" class="btn" data-toggle="tooltip" title="download pdf of the invoice">PDF</button>
                        <button type="button" id="email" class="btn" data-toggle="tooltip" title="email invoice to client">Email</button>
                        <div style="float:right;display:none">
                            <input value="" name="keyword" type="text" class="input-medium search-query">
                            <button style="float:right" class="btn">Go</button>
                        </div>
                    </div>
                </div>


                <table style="margin-top:0px" class="table table-condensed table-hover table-striped table-bordered"
                       cellpadding="0" cellspacing="0" border="0" class="table">


                    <thead>
                    <tr>
                        <th></th>
                        <th>Factuur #</th>
                        <th>ProductID('s) + aantal(len)</th>
                        <th>LeerlingID</th>
                        <th>Datum</th>
                        <th>Totaalbedrag</th>
                        <th>Betaald</th>
                        <th></th>
                    </tr>
                    </thead>

                    <?php
                    /**
                     * @var $facturen Factuur[]
                     */

                    foreach($facturen as $factuur)
                    {
                        ?>
                        <tr>
                            <td><input name="chk[]" type="checkbox" id="c_33" value="33"></td>
                            <td><a rel="popover" data-placement="right"
                                   data-original-title="Invoice # 000032"
                                   data-content="
                                         <div><div>
                                         <div style='padding:10px 0 0 0'><span style='padding:10px 10px 0 0'>Paid Amount</span>�0.00</div>
                                         <div><span style='padding:0 10px 0 0'>Balance Amount</span>�0.00</div>

                                         <hr>
                                         "
                                   href="bewerken.php?uid=32">   <?=$factuur->GetId()?></a>
                            </td>
                            <td>ID2=1&ID3=1</td>
                            <td>
                                <div></div>
                                <div style="font-size:11px"><?=$factuur->GetContact()->id?></div>
                            </td>
                            <td>26-7-2014</td>
                            <td style="text-align:right">&euro; <?= $factuur->GetTotaalBedrag()?></td>
                            <td>JA</td>
                            <td> &nbsp;&nbsp;&nbsp;
                                <a title="Edit Invoice" href="bwerken.php?uid=33">edit</a>
                            </td>


                        </tr>
                    <?php
                    }
                    ?>

                    <tr>
                        <td><input name="chk[]" type="checkbox" id="c_33" value="33"></td>
                        <td><a rel="popover" data-placement="right"
                               data-original-title="Invoice # 000032"
                               data-content="
                                         <div><div>
                                         <div style='padding:10px 0 0 0'><span style='padding:10px 10px 0 0'>Paid Amount</span>�0.00</div>
                                         <div><span style='padding:0 10px 0 0'>Balance Amount</span>�0.00</div>

                                         <hr>
                                         "
                               href="bewerken.php?uid=32">   000032</a>
                        </td>
                        <td>ID2=1&ID3=1</td>
                        <td>
                            <div></div>
                            <div style="font-size:11px">2</div>
                        </td>
                        <td>26-7-2014</td>
                        <td style="text-align:right">&euro; 225.95</td>
                        <td>JA</td>
                        <td> &nbsp;&nbsp;&nbsp;
                            <a title="Edit Invoice" href="bwerken.php?uid=33">edit</a>
                        </td>


                    </tr>


                    <!-- Voor het creeeren van een mooie tussenruimtes tussen de rijen

                                            <tr class="detail" style="display:none">
                                                <td class="borbottomlight" colspan="8">
                                                    <table>

                                                    </table>
                                                </td>
                                            </tr>
                    -->
                </table>

            </form>
            <p>Bovenstaande gegevens worden geladen uit xml/facturen.xml, na een succesvolle iDeal betaling wordt de facturen.xml geedit en hier getoond</p>

        </div>

    </div>
</div>

 