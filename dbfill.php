<?php

$conn;

function kapcsolat()
{
    require('./config.php');
    global $conn;
    mysqli_report(MYSQLI_REPORT_OFF);
    $connect = @mysqli_connect($servername, $username, $password, $dbname);

    if (!$connect) {
        die("Kapcsolódás sikertelen: " . mysqli_connect_error());
    }

    //echo '<h4 style="text-align:center;color:maroon;">Sikeres a kapcsolat !</h4>';
    $conn = $connect;
    return $connect;
}

// Ellenőrzi, hogy az adott táblában (paraméterként átadva) van-e már adat (sor)
function verify($ident, $tabla)
{
    require('./config.php');
    global $conn;
    $db = $dbname;

    $ellenor = "SELECT `$ident` FROM `$db`.$tabla;";
    $eredmeny = mysqli_query($conn, $ellenor);
    $sor = mysqli_fetch_array($eredmeny);

    return $sor;
}

// File beolvasása, darabolása sorokra
function fileBeolvas($allomany)
{
    $lines = file($allomany, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $arrayOfLines = [];
    foreach ($lines as $line) {

        $arrayOfLines[] = explode(';', $line);
    }

    return $arrayOfLines;
}

// -- Torta tábla feltöltése --
function torta_insert()
{
    require('./config.php');
    global $conn;
    $db = $dbname;

    $row = verify('torta_id', 'torta');

    if ($row != NULL) {
        echo '<p class="kicsi">A torta tábla már feltöltve, használatra kész.</p>';
    } else {

        $arrayOfLines = fileBeolvas('tortak.csv');
        $sorok        = [];
        $arrayTemp    = [];
        $arakTomb     = [];

        foreach ($arrayOfLines as $rows) {
            $sorok[] = $rows[1];
        }

        foreach ($sorok as $ar) {
            $arrayTemp[] = explode(':', $ar);
        }

        foreach ($arrayTemp as $arak) {
            $arakTomb[]  =  number_format((float)$arak[0], 2, ',', '');
        }

        $sql = "INSERT INTO `$db`.`torta` (`torta_nev`,`ar`) VALUES (?, ?) ;";

        if ($stmt = mysqli_prepare($conn, $sql)) {

            mysqli_stmt_bind_param($stmt, "si", $torta_nev, $ar);

            for ($i = 0; $i < count($arrayOfLines); $i++) {
                $torta_nev = $arrayOfLines[$i][0];
                $ar        = $arakTomb[$i];
                mysqli_stmt_execute($stmt);
            }
            echo '<p class="kicsi">Torták feltöltve</p>';
        } else {
            echo "Hibás adatbevitel: " . $sql . "<br>" . mysqli_error($conn) . "<br>";
        }
        mysqli_stmt_close($stmt);
    }
}

// -- Fogyasztók tábla feltöltése --
function kolkok_insert()
{
    require('./config.php');
    global $conn;
    $db = $dbname;

    $row = verify('k_id', 'kolkok');

    if ($row != NULL) {
        echo '<p class="kicsi">A tortafogysztók táblája már feltöltve, használatra kész.</p>';

    } else {
        $arrayOfLines = fileBeolvas('tortak.csv');
        $sorok        = [];
        $arrayTemp    = [];
        $nevekOssz    = [];
        $nevekTomb    = [];
        $nevekUniq    = [];

        // A beolvasott sorok tömbjáből a második elemre van szükségem (az is egy tömb)
        foreach ($arrayOfLines as $rows) {
            $sorok[] = $rows[1];
        }

        // Az előzőleg kapott tömböt a ':'-nál szétrobbantom
        foreach ($sorok as $ar) {
            $arrayTemp[] = explode(':', $ar);
        }

        // Az előző szétrobbantás eredménye is egy tömb. Az árak és nevek értékei
        foreach ($arrayTemp as $nevek) {
            $nevekOssz[]  = $nevek[1];
        }

        // A nevek is szétrobbantom a ','-nél
        foreach ($nevekOssz as $nevMind) {
            $nevekTomb[] = explode(',', $nevMind);
        }

        // Az így kapott '$nevekTomb' tömbben sok a redundancia, kiszedem azokat 
        for ($i = 0; $i < count($nevekTomb); $i++) {
            foreach ($nevekTomb[$i] as $nev) {
                $nevekUniq[] = $nev;
            }
        }
        $nevekUniq = array_unique($nevekUniq);

        // az egyedi elemek halmazából feltölthetem a kölkök neveit
        $sql = "INSERT INTO `$db`.`kolkok` (`k_nev`) VALUES (?) ;";

        if ($stmt = mysqli_prepare($conn, $sql)
        ) {

            mysqli_stmt_bind_param($stmt, "s", $k_nev);

            foreach($nevekUniq as $value){
                $k_nev = $value;
                mysqli_stmt_execute($stmt);
            }
           
            echo '<p class="kicsi">Tortafogyasztók feltöltve</p>';
        } else {
            echo "Hibás adatbevitel: " . $sql . "<br>" . mysqli_error($conn) . "<br>";
        }
        mysqli_stmt_close($stmt);
    }
}

// A kapcsolótábla feltöltése a megfelelő ID-vel
function eaten_insert()
{
    require('./config.php');
    global $conn;
    $db = $dbname;

    $row = verify('eat_id', 'eaten');

    if ($row != NULL) {
        echo '<p class="kicsi">Az eaten tábla már feltöltve, használatra kész.</p>';

    } else {
        $arrayOfLines = fileBeolvas('tortak.csv');
        $sorok        = [];
        $arrayTemp    = [];
        $nevekOssz    = [];
        $nevekTomb    = [];       

        foreach ($arrayOfLines as $rows) {
            $sorok[] = $rows[1];
        }       

        foreach ($sorok as $ar) {
            $arrayTemp[] = explode(':', $ar);
        }

        foreach ($arrayTemp as $nevek) {
            $nevekOssz[]  = $nevek[1];
        }
       
        foreach ($nevekOssz as $nevMind) {
            $nevekTomb[] = explode(',', $nevMind);
        }       

        for ($i = 0; $i < count($nevekTomb); $i++) {   

            // Végigmegyek a szétrobbantott nevek tömb mindegyik elemén
            foreach ($nevekTomb[$i] as $nev) {

                // A $nevekTomb[$i] kulcs értéke meg fog egyezni a torták ID-vel
                $kulcsbolID = (array_keys($nevekTomb)[$i] + 1);
                
                // A táblából kikeresem mely névhez melyik ID tartozik:
                $kolokID = "SELECT `k_id` FROM `$db`.`kolkok`WHERE k_nev = '$nev'";
                $result = mysqli_query($conn, $kolokID);
                
                if (mysqli_num_rows($result) > 0) {

                    // Ha megvannak a kölkök neváhez tartozó ID, a $row1 tömbbe beolvasva
                    // akkor azt már be tudom majd szúrni a kapcsolótáblába
                    while ($row1 = mysqli_fetch_assoc($result)) {
                       
                        // Megvan a tortákhoz tarozó ID, megvan a kölkökhöz tartozó ID,
                        // így fel tudom tölteni a kapcsolótáblát:
                        $sql2 = "INSERT INTO `$db`.`eaten` (`k_id`,`torta_id`) VALUES (?, ?) ;";
                        if ($stmt = mysqli_prepare($conn, $sql2)) {

                            mysqli_stmt_bind_param($stmt, "ii", $k_id, $torta_id);
                                $k_id     = $row1["k_id"];
                                $torta_id = $kulcsbolID;
                                mysqli_stmt_execute($stmt);                            
                            //echo '<p class="kicsi">Fogyasztás tábla feltöltve</p>';

                        } else {
                            echo "Hibás adatbevitel: " . $sql2 . "<br>" . mysqli_error($conn) . "<br>";
                        }
                        mysqli_stmt_close($stmt);

                    }
                } else {
                    echo "Nem találtam ehhez a névhez tartozó ID-t";
                }  
               
            }
        }
      
    }
}
