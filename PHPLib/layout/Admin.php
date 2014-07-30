<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php if(isset($_GET['q']) && substr($_GET['q'], -1) == '/')
    {
        //zorg dat er een goede base url wordt meegegeven
        ?>
        <base href="../" />
    <?php
    }
    ?>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Drive Perfect Facturatiesysteem</title>
    <link href="fonts/fonts.css" rel="stylesheet" type="text/css" />

    <link href="style/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    <style>
        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
            /* add padding to account for vertical scrollbar */
            padding-right: 20px;
        }
        /* IE 6 doesn't support max-height
         * we use height instead, but this forces the menu to always be this tall
         */
        * html .ui-autocomplete {
            height: 100px;
        }
    </style>


    <link rel="stylesheet" href="style/themes/base/jquery.ui.all.css">

    <script src="js/jquery.js" type="text/javascript"></script>



    <script src="js/ui/jquery.ui.core.js" type="text/javascript"></script>
    <script src="js/ui/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="js/ui/jquery.ui.position.js" type="text/javascript"></script>
    <script src="js/ui/jquery.ui.autocomplete.js" type="text/javascript"></script>
    <script src="js/ui/jquery.ui.datepicker.js" type="text/javascript"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />


    <script type="text/javascript">
        $(function () {

            $('#myTab a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

            $('#tab-invoice-notes a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });



            $("[rel=tooltip]").tooltip();
            $("[rel=popover]").popover({trigger:'hover',html:true});

        });
    </script>





</head>

<body>

<div id="wrap">
<div id="header">
    <div class="logo"><a href="#"></a></div>

    <div class="topstrip">
        <div class="rightside">
            <p><strong>Welkom</strong><span>Admin</span></p>
            <a href="" class="logout">Uitloggen</a>
        </div>
    </div>

    <div id="nav">

        <div class="nav">
            <ul>
                <li><a   <?php if(!isset($_GET['page']) || $_GET['page'] == "facturen" || $_GET['page'] == "nieuwefactuur"){?>class="active"<?php } ?> href="facturen/">Facturen</a></li>
                <li><a   <?php if(isset($_GET['page']) && $_GET['page'] == "producten"){?>class="active"<?php } ?> href="producten/">Producten</a></li>
                <li><a   <?php if(isset($_GET['page']) && $_GET['page'] == "leerlingen"){?>class="active"<?php } ?> href="leerlingen/">Leerlingen</a></li>
            </ul>

        </div>
    </div>

    <?php $this->SetView();?>



<script type="text/javascript">
    $(document).ready(function () {
        $("#various2").fancybox();
    });
</script>


</div>


<script type="text/javascript">
    $(function () {

        $('#test').tooltip();

    });
</script>


</body>
</html>
