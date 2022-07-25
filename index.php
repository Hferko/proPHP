<?php
require_once('dbcreate.php');
require_once('dbfill.php');
//require_once('query.php');
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangai házi feladat</title>
    <style>
        body {
            background-color: maroon;
            font-family: 'Lucida Sans', sans-serif;
            text-align: center;
        }

        main,
        .card {
            width: 85%;
            padding: 0.5vw;
            background-color: beige;
            margin: 10px auto;
            border: thin solid aqua;
            border-radius: 8px;
            box-shadow: 6px 8px 6px lavender;
        }

        .kicsi{font-size: small;}

        hr {
            width: 60%;
            color: #ddd;
        }

        table {
            border-collapse: collapse;
            width: 60%;
            margin: 20px auto;
            
        }

        th,
        td {
            text-align: center;
            padding: 8px;
            /*border: 1px solid navy;*/
        }

        tr:nth-child(even) {
            background-color: #D6EEEE;
        }

        tr:hover {
            background-color: white;
        }

        th {
            background-color: brown;
            color: white;
        }

       
    </style>
</head>

<body>
    <main>
        <h4>Adatbázis létrehozása</h4>
        <div class="card">

            <?php
            $conn = nyit();
            echo '<hr>';
            createdb();
            echo '<hr>';
            kolkok_table();
            echo '<hr>';
            torta_table();
            echo '<hr>';
            eaten_table();
            echo '<hr>';
            ?>
        </div>
        <h4>Adatbázis feltöltése</h4>
        <div class="card">
            <?php
            torta_insert();
            echo '<hr>';           
            kolkok_insert();
            echo '<hr>';
            eaten_insert();
            echo '<hr>';
            ?>
        </div>
        <div class="card">
            <?php
             $header1 = ['Ünneplő neve', 'Fogyasztot torták száma'];
             $header2 = ['Csak egy tortát fogyasztott'];
             require_once('query.php');
             
            ?>
        </div>

        <div class="card">
            <?php if (mysqli_close($conn)) {
                echo '<h4 style="text-align:center;color:maroon;">A MySql kapcsolat bontva.</h4>';
            } else {
                echo '<h4 style="text-align:center;color:maroon;">A kapcsolat a szerverel még él !!</h4>';
            }
            ?>
        </div>
    </main>
</body>

</html>