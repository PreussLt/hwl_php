<?php
include("sql.php");
include("../Template.class.php");
function getResList($id) //Alle reservierungen des nutzers
{

    $reservierungen = array();
    $conn = mysql();
    $sql = "SELECT * FROM reservierung WHERE m_ID = $id";
    $res = $conn->query($sql) or die($conn->error); // reservierungen holen
    while ($row = $res->fetch_assoc()) {
        $reservierungen[] = $row;
    }
    return $reservierungen;
}


$htmlText = "";
$ress = getResList($_GET['id']);
if (count($ress) > 0) {
    $tpl = new Template("../includes/");
    $htmlText .= "<ul class='list-group'>";
    foreach ($ress as $res) { // jede Reservierung zur Liste hinzufügen
        $tpl->load("reservierungenListItem.html");

        $tpl->assign("startDatum", $res['res_datum_start']);
        $tpl->assign("endDatum", $res['res_datum_end']);
        $tpl->assign("res_id",$res['res_ID']);
        $tpl->assign("m_id",$res['m_ID']);
        $tpl->assign("res_zweck",$res['res_zweck']);
        $tpl->assign("f_id",$res['f_ID']);
        $tpl->assign("kennzeichen","asd");
        $htmlText .= $tpl->getTemplateContent();
    }
    $htmlText .= "</ul>";
} else {
    $htmlText = "<span class='keineReservierung'>Keine Reservierungen vorhanden.</span>";
}


echo $htmlText;
