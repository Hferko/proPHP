<?php
require_once('./dbfill.php');
$conn = kapcsolat();

function torta_evok(array $head)
{
    require('./config.php');
    global $conn;
    $db = $dbname;

    $tortak_szama = "SELECT `kolkok`.`k_nev` AS 'Ünneplő neve', COUNT(`eaten`.`torta_id`) AS 'Torták száma' 
                    FROM (`$db`.`kolkok` INNER JOIN `eaten` ON `kolkok`.`k_id`=`eaten`.`k_id`)
                    GROUP BY  `kolkok`.`k_id` ORDER BY `Torták száma` ASC;";

    $eredmeny = mysqli_query($conn, $tortak_szama);
    $sorokSzama = mysqli_num_rows($eredmeny);

    if ($sorokSzama > 0) {       
        $header = $head;

        // --- A táblázat ------    
        $countHead =  count($header);

        //Táblázat fejléce
        echo "<table><tr>";

        for ($i = 0; $i < $countHead; $i++) {
            echo ('<th>' . $header[$i] . '</th>');
        };
        echo "</tr>";

        while ($row = mysqli_fetch_array($eredmeny)) {

            echo "<tr>";
            echo "<td>" . $row['Ünneplő neve'] . "</td>";
            echo "<td>" . $row['Torták száma'] . " féle torta</td>";
        }
        echo "</table>";
    }
}
torta_evok($header1);

function nem_habzsol(array $head)
{
    require('./config.php');
    global $conn;
    $db = $dbname;

    $nem_habzsol  = "SELECT `kolkok`.`k_nev` AS 'Ünneplő neve', COUNT(`eaten`.`torta_id`) AS 'Torták száma'
    FROM (`$db`.`kolkok` INNER JOIN `eaten` ON `kolkok`.`k_id`=`eaten`.`k_id`)   
    WHERE 'Torták száma' < 2
    GROUP BY  `kolkok`.`k_id`
    ORDER BY `Torták száma` ASC LIMIT 6;";

    $result = mysqli_query($conn, $nem_habzsol);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {       
        $header = $head;

        // --- A táblázat ------    
        $countHead =  count($header);

        //Táblázat fejléce
        echo "<table><tr>";

        for ($i = 0; $i < $countHead; $i++) {
            echo ('<th>' . $header[$i] . '</th>');
        };
        echo "</tr>";

        while ($row = mysqli_fetch_array($result)) {

            echo "<tr>";
            echo "<td>" . $row['Ünneplő neve'] . "</td>";
            //echo "<td>" . $row['Torták száma'] . " féle torta</td>";
        }
        echo "</table>";
    }
}
nem_habzsol($header2);
