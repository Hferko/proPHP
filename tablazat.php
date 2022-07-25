<?php
function tablaRajz($ered, array $header, $oszlop1, $oszlop2)
{    
    // --- A táblázat ------    
    $countHead =  count($header);
    
    //Táblázat fejléce
    echo "<table><tr>";

    for ($i = 0; $i < $countHead; $i++) {
        echo ('<th>' . $header[$i] . '</th>');
    };
    echo "</tr>";

    while ($row = mysqli_fetch_array($ered)) {
      
        echo "<tr>";
        echo "<td>" . $row[$oszlop1] . "</td>";
        echo "<td>" . $row[$oszlop2] . "</td>";
        //echo "<td>" . $row['kod'] . "</td>";
        //echo "<td style='text-align:center;'>" . $row['id'] . "</td>";
        /*echo "<td style='text-align:right;'><div class='btn-group'>
                                <button class='button' onclick='modal1()'>Módosítás</button>
                                <button class='button'onclick='modal2()'>Törlés</button> 
                              </div> 
                          </td>";
        echo "</tr>";*/
    }
    echo "</table>";
}
