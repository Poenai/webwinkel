<?php
/**
 * @var $producten Product[]
 * @var $factuur Factuur
 */
?>
<div id="sub">
    <ul>
        <li><a href="<?php if(!self::_rewrite) print "?page="; ?>facturen/">Toon facturen</a></li>
        <li><a href="<?php if(!self::_rewrite) print "?page="; ?>nieuwefactuur/" class="active">Maak nieuw factuur</a></li>
    </ul>
</div>
</div>

<script>
    var curr_symbol;
    var item_codes;
    var customers;
    curr_symbol = "€";

    var arr = new Array();

    <?php
    $itemcodes = array();
    foreach($producten as $index => $product)
    {
        ?>
    arr[<?=$index?>] = new Array();
    arr[<?=$index?>][0] = '<?=$product->id?>'; //productcode
    arr[<?=$index?>][1] = '<?=$product->name?>';
    arr[<?=$index?>][2] = '<?=$product->description?>';
    //prijs is exclusief BTW
    arr[<?=$index?>][3] = '<?=($product->price * (100 + $product->BTWpercentage)) / 100?>';
    arr[<?=$index?>][4] = '<?=$product->price * $product->BTWpercentage / 100?>';
    arr[<?=$index?>][5] = '';
    <?php
        $itemcodes[] = $product->id;
    }
    ?>

    item_codes = <?= json_encode($itemcodes)?>;


    var arr_customers = new Array();

    arr_customers[0] = new Array();
    arr_customers[0][0] = 'test';
    arr_customers[0][1] = '001';
    arr_customers[0][2] = '';


    arr_customers[1] = new Array();
    arr_customers[1][0] = 'ddan';
    arr_customers[1][1] = '567567567';
    arr_customers[1][2] = '';


    arr_customers[2] = new Array();
    arr_customers[2][0] = 'Leejay Hall';
    arr_customers[2][1] = '123';
    arr_customers[2][2] = '';

    customers =['test','ddan','Leejay Hall','pp','PRAKASH SINSH','vvv'];

</script>


<script src="js/invoice.js" type="text/javascript"></script>
<script src="js/util.js" type="text/javascript"></script>


<script type="text/javascript">
    $(document).ready(function () {

        $('#submitx').click(function () {

            if ($('#customer_id').val() == "select") {
                alert("Please Select Customer");
                return;
            }

            if ($('#invoice_name').val() == "") {
                alert("Please Enter Invoice Name");
                return;
            }
            if ($('#rowcount').val() >= 1) {
                if (isitemsvalidated() == false) {
                    alert("Please Add Items");
                    return;
                }
            } else {
                return;
            }

            document.getElementById("thisform").submit();

        });

        $('#customer_id').change(function () {

            $.getJSON("getAddress.php", {id:$(this).val(), ajax:'true'}, function (j) {
                var options = '';

                for (var i = 0; i < j.length; i++) {
                    // options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                    $("#bill_1").val(j[i].bill_1);
                    $("#bill_2").val(j[i].bill_2);
                    $("#bill_3").val(j[i].bill_3);
                    $("#bill_4").val(j[i].bill_4);

                    $("#ship_1").val(j[i].ship_1);
                    $("#ship_2").val(j[i].ship_2);
                    $("#ship_3").val(j[i].ship_3);
                    $("#ship_4").val(j[i].ship_4);

                }

            })

        });
    });
</script>

<div id="container">
    <div id="content">
        <div id="invoicexc">


            <form action="<?php if(!self::_rewrite) print "?page="; ?>savefactuur/" method="post" id="thisform">


                <input id='rowcount' name='rowcount' type='hidden' value='1'>


                <div style="width:100%;margin-top:10px" class="clearfix">
                    <div class="block_top_1">
                        <table class="table table-condensed table-disable-hover" style="margin-bottom:0px">
                            <tr class="info">
                                <td colspan="2"><b>
                                        FACTUUR gegevens

                                    </b></td>
                            </tr>
                            <tr>
                                <td>Factuur#</td>
                                <td><input name="invoice_number" type="text" readonly="readonly" value="<?=$factuur->GetId()?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Factuurdatum</td>
                                <td><input name="invoice_date" style="width:100px;margin-bottom:0px" type="text" class="textbox date"
                                           id="invoice_date" /></td>
                            </tr>
                            <script>
                                $(document).ready(function(){
                                    //stel de juiste tijd in van de factuur
                                    //vage bug die maand +1 doet
                                    //mischien heeft het iets te maken dat ze een array gebruiken waarvan het een element een index van 0 heeft
                                    $( "#invoice_date" ).datepicker("setDate", new Date(<?= date("Y,n-1,d" , $factuur->getFactuurDatum()) ?>) );
                                });
                            </script>
                        </table>
                    </div>

                    <div class="block_top_2">
                        <table class="table table-condensed table-disable-hover" style="margin-bottom:0px">
                            <tr class="info">
                                <td colspan="2"><b>KLANT gegevens</b></td>
                            </tr>
                            <tr>
                                <td>Klant ophalen</td>
                                <td><select name="customer_id" id="customer_id" class="drop-down">
                                        <option value="select" >--select--</option>
                                        <?php
                                        /**
                                         * @var $klanten Contact[]
                                         * @var $klantId int
                                         */
                                        foreach($klanten as $klant)
                                        {
                                            //houd de user die op de factuur selected
                                            ?>
                                            <option <?php if($klantId == $klant->id) {print 'selected="selected" ';} ;?>value="<?=$klant->id?>"><?=$klant->naam?></option>
                                        <?php
                                        }
                                        ?>

                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>


                    <div class="block_top_3">

                        <table class="table table-condensed table-disable-hover">
                            <tr class="info">
                                <td colspan="2"><b>BETALINGS gegevens</b></td>
                            </tr>
                            <tr>
                                <td>Betalingswijze</td>
                                <td><select class="drop-down" name="payment_method" style="width:150px">
                                        <option><?=$factuur->getBetalingswijze()?></option>
                                        <option> Cash</option>
                                        <option> Overmaking</option>
                                        <option> iDeal</option>

                                    </select></td>
                            </tr>

                        </table>


                    </div>

                </div>


                <h2 style="font-size:15px">PRODUCT(EN)</h2>
                <table id="tablemain" class="table table-condensed  table-bordered table-disable-hover">

                    <thead>
                    <tr>
                        <th width="179">ProductID</th>
                        <th width="180">Naam</th>
                        <th width="176">Bedrag</th>
                        <th width="100">Aantal</th>
                        <th width="100">BTW</th>
                        <th width="143">SubTotaal</th>
                        <th width="36">&nbsp;</th>
                    </tr>
                    </thead>

                    <?php
                    foreach($factuur->GetAllFactuurRegels() as $regel)
                    {
                        ?>
                        <tr class="detail" id="item_row_1">
                            <td><input value="<?=$regel->GetId()?>" name="item[]"  type="text"
                                       style="border-width:0px;overflow:hidden;width:95%"/></td>
                            <td><input name="item_name[]" value="<?=$regel->GetName()?>"  type="text" style="border-width:0px;overflow:hidden;width:95%"/></td>
                            <td>
                                <div class="number" style="text-align: right;padding-right:5px "><?=$regel->GetProduct()->price?></div>
                            </td>
                            <td><input name="qty[]"" value="<?=$regel->GetAantal()?>" type="text"
                                style="border-width:0px;overflow:hidden;width:93%;padding-right:5px"/></td>
                            <td>
                                <div class="number" style="text-align: right;padding-right:5px "><?=($regel->GetBedragIncl()/(100 + $regel->GetBTW())) *  $regel->GetBTW()?></div>
                            </td>

                            <td class="borbottomlight borleftlight">
                                <div style="text-align: right;padding-right:5px " class="number"><?=$regel->GetBedragIncl()?></div>
                            </td>

                            <td style="vertical-align:middle;" class="borbottomlight borleftlight"><a name="remove[1]" id="remove_1"
                                                                                                      href="javascript:void(0)"
                                                                                                      class="deleterecord"><i
                                        class="icon-trash"></i></a></td>
                        </tr>
                    <?php
                    }
                    ?>




                </table>

                <table class="table_last" cellpadding="0" cellspacing="0" border="0" style="border:none;" width="100%">
                    <tr class="total">
                        <td width='75%' valign="top" class="bortopbig"><a id="add-item" href="javascript:void(0);"
                                                                          class="btn btn-info additem">Nieuw product</a></td>
                        <td class="bgcolor bor-left bor-top bor-bot"><span>Totaal ex BTW :</span></td>
                        <td class="bgcolor bor-left bor-top bor-bot bor-right">
                            <div id='sub-total'>&euro;0.00</div>
                        </td>
                    </tr>
                    <tr class="total">
                        <td>&nbsp;</td>
                        <td class="bgcolor bor-left bor-bot"><span>21% BTW :</span></td>
                        <td class="bgcolor bor-left bor-right bor-bot">
                            <div id='tax-total'>&euro;0.00</div>
                        </td>
                    </tr>
                    <tr class="total">
                        <td>&nbsp;</td>
                        <td class="bgcolordark total bor-left bor-bot"><span class="total"><strong>Totaalbedrag</strong></span></td>
                        <td class="bgcolordark total bor-left bor-bot bor-right">
                            <div class="total">
                                <div id='total'>&euro;0.00</div>
                            </div>
                        </td>

                        <input type="hidden" id="hidden_total" name="hidden_total" value=""/><input type="hidden" id="hidden_total_num"
                                                                                                    name="hidden_total_num" value=""/>

                    </tr>

                </table>


                <input type="button" id="submitx" class=" 	btn btn-primary" value="Factuur opslaan"/>
            </form>
        </div>
    </div>
</div>
</div>