<?php
include("../Template.class.php");
include("sql.php");
$sql = "SELECT * FROM mitarbeiter";
if(isset($_GET['q']) && !empty($_GET['q'])){ //Wenn Suchbegriff eingegeben
    $sql .= " WHERE (mitarbeiter.m_vname LIKE \"%" . $_GET['q']. "%\" OR mitarbeiter.m_nname LIKE \"%". $_GET['q'] . "%\")";
}
$conn = mysql();
$tpl = new Template("../includes/");
$res = $conn->query($sql) or die("fehler"); //Alle gefundenen Benutzer holen
while($row = $res->fetch_assoc()){ //Jeden Benutzer als mitarbeiterCard anzeigen
    $tpl->load("mitarbeiterCard.html");
    $tpl->assign("vorname",$row['m_vname']);
    $tpl->assign("nachname",$row['m_nname']);
    $id = $row['m_ID'];
    $tpl->assign("fahrten",getFahrten($id));
    $tpl->assign("reservierungen",getReservierungen($id));
    $tpl->assign("mitarbeiterLvl",getMitarbeiterLvl($id));
    $tpl->assign("id",$id);
    $tpl->display();
}

function getFahrten($id) //anzahl gefahrener fahrten
{
    $sql = "SELECT * FROM fahrt WHERE m_ID = $id";
    $conn = mysql();
    $res = $conn->query($sql);
    $num_rows = $res->num_rows;
    return $num_rows;
}

function getReservierungen($id) //anzahl der reservierungen
{
    $sql = "SELECT * FROM reservierung WHERE m_id = $id";
    $conn = mysql();
    $res = $conn->query($sql);
    $num_rows = $res->num_rows;
    return $num_rows;
}

function getMitarbeiterLvl($id){ //Mitarbeiterlvl als string
    $sql = "SELECT mitarbeiter.m_lvl_ID, m_level.m_level_name as lvl FROM mitarbeiter 
            INNER JOIN m_level ON mitarbeiter.m_lvl_ID = m_level.m_level_ID
            WHERE mitarbeiter.m_ID = $id";
    $conn = mysql();
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    return $row['lvl'];
}