<div id="sub">

    <ul>
        <li><a href="../customer/view-customers.php" class="active">Toon leerlingen</a></li>
        <li><a href="../customer/add-customer.php">Nieuwe leerling</a></li>
    </ul>
</div>
</div>
<div id="container">
    <div id="content">
        <div id="viewcustomer">
            <form action="">
                <table  class="table table-condensed table-hover table-striped table-bordered" cellpadding="0" cellspacing="0" border="0" >


                    <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Straat+nr</th>
                        <th>Postcode </th>
                        <th>Woonplaats</th>
                        <th>Telefoon</th>
                        <th>BSN</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                    </thead>


                    <?php
                    /**
                     * @var $leerlingen Contact[]
                     */

                    foreach($leerlingen as $leerling)
                    {
                        ?>
                        <tr>
                            <td><?=$leerling->naam?></td>
                            <td><?=$leerling->straat." ".$leerling->huisnummer?></td>
                            <td><?=$leerling->postcode?></td>
                            <td><?=$leerling->plaats?></td>
                            <td><?=$leerling->telefoon?></td>
                            <td><?=$leerling->BSN?></td>
                            <td><?=$leerling->email?></td>
                            <td><a href="">Edit</a>&nbsp;&nbsp;<a href="">Delete</a></td>
                        </tr>
                        <?php
                    }

                    ?>
                </table>
            </form>

            <p>Bovenstaande gegevens worden geladen vanuit XML (xml/contacts.xml)</p>

        </div>
    </div>
</div>
</div>