<?php
include("sql.php");
include("../Template.class.php");
$row = [];
if (!empty(trim($_GET['id']))) {
    $sql = "SELECT fahrzeug.f_ID,fahrzeug.kennzeichen_ID as k_ID, fs_klasse.fsk_bezeichnung as fs_klasse,fahrzeug.f_hersteller,fahrzeug.f_model,
                       fahrzeug.f_typ,fahrzeug.f_kaufdatum,f_karroserie.f_karroserie_bezeichnung as karroserie,
                       fahrzeug.f_farbe,m_level.m_level_name as m_level,f_kennzeichen.kennzeichen,
                       f_kmstand.fkm_wert as kilometerstand
                FROM   fahrzeug
                INNER JOIN fs_klasse ON fahrzeug.fsk_ID = fs_klasse.fsk_ID
                INNER JOIN f_karroserie ON fahrzeug.f_karroserie_ID = f_karroserie.f_karroserie_ID
                INNER JOIN m_level ON fahrzeug.m_level_ID = m_level.m_level_ID
                INNER JOIN f_kennzeichen ON fahrzeug.kennzeichen_ID = f_kennzeichen.kennzeichen_ID
                INNER JOIN f_kmstand ON fahrzeug.f_ID = f_kmstand.f_ID
                WHERE  fahrzeug.f_ID = " . $_GET['id'];
    $conn = mysql();
    $res = $conn->query($sql) or die($conn->error); //Fahrzeug details holen
    $row = $res->fetch_assoc();
} else {
    header('Location: /');
}
$carText = $row['f_hersteller'] . ' ' . $row['f_typ'] . ' ' . $row['f_model'];


$tpl = new Template("../includes/");

$tpl->load("fahrzeugDetail.html");
$tpl->assign("cartext", $carText);
$tpl->assign("f_ID", $_GET['id']);
$tpl->assign("k_ID", $row['k_ID']);
$tpl->assign("f_hersteller", $row['f_hersteller']);
$tpl->assign("f_typ", $row['f_typ']);
$tpl->assign("f_model", $row['f_model']);
$tpl->assign("f_farbe", $row['f_farbe']);
$tpl->assign("karroserie", $row['karroserie']);
$tpl->assign("kennzeichen", $row['kennzeichen']);
$tpl->assign("kilometerstand",$row['kilometerstand'] . ' km');
$d = new DateTime($row['f_kaufdatum']);
$fancyDateText = $d->format('d.m.Y');
$tpl->assign("f_kaufdatum", $fancyDateText);
$tpl->assign("fs_klasse", $row['fs_klasse']);
$tpl->assign("m_level", $row['m_level']);
$tpl->display();
