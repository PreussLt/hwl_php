<?php
session_start();
include("../Template.class.php");
include("sql.php");

function getKarroserieOptions(){ //kennzeichen dropdown items
    $sql = "SELECT f_karroserie_ID as ID, f_karroserie_bezeichnung as bezeichnung FROM f_karroserie";
    $conn = mysql();
    $res = $conn->query($sql) or die($conn->error);
    $karros = [];
    while($row = $res->fetch_assoc()){
        $karros[] = $row;
    }

    $karrosOptionsHtml = "";

    foreach ($karros as $kar){
        $option = "<option value='".$kar["ID"]."'>".$kar["bezeichnung"]."</option>\n";
        $karrosOptionsHtml .= $option;
    }

    return $karrosOptionsHtml;
}

function getMitarbeiterLevelOptions(){//Mitarbeiter dropdown items
    $sql ="SELECT m_level_ID as ID, m_level_name as lvl FROM m_level";
    $conn = mysql();
    $res = $conn->query($sql) or die($conn->error);
    $levels = [];
    while($row = $res->fetch_assoc()){
        $levels[] = $row;
    }

    $mitarbeiterLevelHtml = "";

    foreach($levels as $lvl){
        $option = "<option value='".$lvl['ID']."'>".$lvl['lvl']."</option>\n";
        $mitarbeiterLevelHtml .= $option;
    }

    return $mitarbeiterLevelHtml;
}

function getFuehrerscheinOptions(){ //führerscheinlvl dropdown items
    $sql = "SELECT fsk_ID as ID, fsk_bezeichnung as bezeichnung FROM fs_klasse";
    $conn = mysql();
    $res = $conn->query($sql) or die($conn->error);
    $options = [];
    while($row = $res->fetch_assoc()){
        $options[] = $row;
    }

    $html = "";

    foreach($options as $option){
        $fs = "<option value='".$option['ID']."'>".$option['bezeichnung']."</option>\n";
        $html .= $fs;
    }

    return $html;
}


$tpl = new Template("../includes/");
$tpl->load("fahrzeug_anlegen.html");
$tpl->assign("actionLink","src/fahrzeugAnlegenRequestHandler.php");
$tpl->assign("karroserieOptions",getKarroserieOptions());
$tpl->assign("mitarbeiterLevelOptions",getMitarbeiterLevelOptions());
$tpl->assign("fuehrerscheinOptions",getFuehrerscheinOptions());
$tpl->display();
