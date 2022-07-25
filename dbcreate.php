<?php
$conn;

function nyit()
{
    require('./config.php');
    global $conn;
    
    mysqli_report(MYSQLI_REPORT_OFF);
    $connect = @mysqli_connect($servername, $username, $password);
    if (!$connect) {       
        die("KAPCSOLÓDÁS SIKERTELEN: " . mysqli_connect_error());        
    }

    echo '<p class="kicsi">Sikeres a kapcsolat a szerverrel!</p>';    
    $conn = $connect;
    return $connect;
}

// Adatbázis
function createdb()
{
    require('./config.php');
    global $conn;   
    $dbneve = $dbname;   

    $adatbazis = "CREATE DATABASE IF NOT EXISTS `$dbneve` CHARACTER SET utf8 COLLATE utf8_general_ci";

    if ((mysqli_query($conn, $adatbazis))) {
        if (mysqli_warning_count($conn) == 0) {
            echo '<p class="kicsi">Sikeresen létrehozva a ' . $dbneve . ' adatbázis</p>';

        } else {
            echo '<p class="kicsi">"' . $dbneve . '" nevű adatbázis már létezik.</p>';     

        }
    } else {
        echo "Nem sikerült létrehozni az adatbázist" . mysqli_error($conn);        
    }
}

// Kölkök tábla
function kolkok_table()
{
    require('./config.php');
    global $conn;

    $db = $dbname;
    $kolkok = "CREATE TABLE IF NOT EXISTS `$db`.`kolkok` (
                    `k_id` INT(3) NOT NULL AUTO_INCREMENT ,  
                    `k_nev` VARCHAR(40) NOT NULL , 
                    UNIQUE (`k_nev`),
                    PRIMARY KEY  (`k_id`)                    
                    )CHARACTER SET utf8mb4;";

    if (mysqli_query($conn,  $kolkok)) {
        echo '<p class="kicsi">A "kolkok" nevű tábla létrehozása sikeres.</p>';
    } else {
        echo "Tábla létrehozása sikertelen: " . mysqli_error($conn) . '<hr>';
    }
}

// Torta tábla
function torta_table()
{
    require('./config.php');
    global $conn;

    $db = $dbname;
    $torta = "CREATE TABLE IF NOT EXISTS `$db`.`torta` (
                    `torta_id` INT(3) NOT NULL AUTO_INCREMENT ,  
                    `torta_nev` VARCHAR(50) NOT NULL ,  
                    `ar` FLOAT NOT NULL ,                     
                    PRIMARY KEY  (`torta_id`)                               
                    )CHARACTER SET utf8mb4;";

    if (mysqli_query($conn,  $torta)) {
        echo '<p class="kicsi">A "torta" nevű tábla létrehozása sikeres.</p>';
    } else {
        echo "Tábla létrehozása sikertelen: " . mysqli_error($conn) . '<hr>';
    }
}

// eaten tábla
function eaten_table()
{
    require('./config.php');
    global $conn;

    $db = $dbname;
    $eaten = "CREATE TABLE IF NOT EXISTS `$db`.`eaten` (
                    `eat_id` INT(3) NOT NULL AUTO_INCREMENT ,                      
                    `k_id` INT(4),
                    `torta_id` INT(4),
                    PRIMARY KEY  (`eat_id`),
                    INDEX (`k_id`),
                    FOREIGN KEY (`k_id`) REFERENCES kolkok(`k_id`),
                    INDEX (`torta_id`),
                    FOREIGN KEY (`torta_id`) REFERENCES torta(`torta_id`)                       
                    );";

    if (mysqli_query($conn,  $eaten)) {
        echo '<p class="kicsi">Az "eaten" nevű tábla létrehozása sikeres.</p>';
    } else {
        echo "Tábla létrehozása sikertelen: " . mysqli_error($conn) . '<hr>';
    }
}