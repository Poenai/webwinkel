<div id="sub">

    <ul>
        <li><a href="<?php if(!self::_rewrite) print "?page="; ?>producten/" class="active">Toon producten</a></li>
        <li><a href="#">Nieuwe product</a></li>
    </ul>
</div>
</div>

<div id="container">
    <div id="content" class="setting-container">
        <div id="viewcustomer">
            <form action="">
                <table  class="table table-condensed table-hover table-striped table-bordered" cellpadding="0" cellspacing="0" border="0" >
                    <tr >
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Unit Value</th>
                        <th>BTW Percentage</th>

                    </tr>


                    <?php
                    /**
                     * @desciption een array met alle producten
                     * @var $producten Product[]
                     */

                    foreach($producten as $product)
                    {
                        ?>
                        <tr >
                            <td ><?= $product->id?></td>
                            <td ><?= $product->name?></p></td>
                            <td ><?= $product->description?></p></td>
                            <td >&euro;<?= $product->price?></td>
                            <td ><?= $product->BTWpercentage?> &#37;</td>

                            <td><a href="edit-item.php?uid=51">Edit</a>&nbsp;&nbsp;<a href="action/xt_delete_item.php?uid=51" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a></td>

                        </tr>
                    <?php
                    }

                    ?>




                    <!-- <tr >
                        <td >001a</td>
                        <td >Website</p></td>
                        <td >joomla website</p></td>
                        <td >&euro;500.00</td>
                        <td >&euro;9.00</td>

                        <td><a href="edit-item.php?uid=52">Edit</a>&nbsp;&nbsp;<a href="action/xt_delete_item.php?uid=52" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a></td>

                    </tr>


                    <tr >
                        <td >piet</td>
                        <td >piet</p></td>
                        <td >Piets Fiets</p></td>
                        <td >&euro;5.00</td>
                        <td >&euro;2.00</td>

                        <td><a href="edit-item.php?uid=53">Edit</a>&nbsp;&nbsp;<a href="action/xt_delete_item.php?uid=53" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a></td>

                    </tr>-->


                </table>





            </form>
            <p>Bovenstaande gegevens worden geladen vanuit XML (xml/producten.xml)</p>
        </div>
    </div>
</div>
</div>