<?php session_start();

//include Template-Klasse
include("../Template.class.php");



function getList()
{
    include("sql.php");

    $fahrzeuge = array();

    if (isset($_SESSION['bypass'])) {

        $gueltigBis = new DateTime($_SESSION['fs_gueltigkeit']);
        $jetzt = new DateTime("now");
        if($gueltigBis > $jetzt){

            $sql = "SELECT fahrzeug.f_ID,fs_klasse.fsk_bezeichnung as fs_klasse,fahrzeug.f_hersteller,fahrzeug.f_model,
                       fahrzeug.f_typ,fahrzeug.f_kaufdatum,f_karroserie.f_karroserie_bezeichnung as karroserie,
                       fahrzeug.f_farbe,m_level.m_level_name as m_level,f_kennzeichen.kennzeichen
                FROM   fahrzeug
                INNER JOIN fs_klasse ON fahrzeug.fsk_ID = fs_klasse.fsk_ID
                INNER JOIN f_karroserie ON fahrzeug.f_karroserie_ID = f_karroserie.f_karroserie_ID
                INNER JOIN m_level ON fahrzeug.m_level_ID = m_level.m_level_ID
                INNER JOIN f_kennzeichen ON fahrzeug.kennzeichen_ID = f_kennzeichen.kennzeichen_ID
                WHERE  fahrzeug.fsk_ID <= " . $_SESSION['fuehrerscheinLVL'] . "
                AND    fahrzeug.m_level_ID <= " . $_SESSION['m_level'];
            $conn = mysql();
            $res = $conn->query($sql) or die($conn->error); //alle verfügbaren Fahrzeuge holen
            while ($row = $res->fetch_assoc()) {
                $fahrzeuge[] = $row;
            }//END while ($row = $res->fetch_assoc())
        }else{
            echo "führerschein ungültig";
        }// END if($gueltigBis > $jetzt)
    } // END if (isset($_SESSION['bypass']))

    return $fahrzeuge;
}

$fahrzeuge = getList();
$tpl = new Template("../includes/");
foreach ($fahrzeuge as $fahrzeug) { //jedes Fahrzeug in liste einfügen
    $carText = $fahrzeug['f_hersteller'].' '.$fahrzeug['f_typ'].' '.$fahrzeug['f_model'];
    

    
    //List item laden
    $tpl->load("fahrzeugListItem.html");

    //platzhalter ersetzen
    $tpl->assign("cartext",$carText);
    $tpl->assign("f_ID",$fahrzeug['f_ID']);
    $tpl->assign("f_hersteller",$fahrzeug['f_hersteller']);
    $tpl->assign("f_typ",$fahrzeug['f_typ']);
    $tpl->assign("f_model",$fahrzeug['f_model']);
    $tpl->assign("f_farbe",$fahrzeug['f_farbe']);
    $tpl->assign("kennzeichen",$fahrzeug['kennzeichen']);

    // template anzeigen
    $tpl->display();

}// END foreach ($fahrzeuge as $fahrzeug)
?>
