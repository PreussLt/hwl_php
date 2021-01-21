<?php
session_start();
include("sql.php");
include("../Template.class.php");

$sql = "SELECT mitarbeiter.m_ID, mitarbeiter.m_vname, mitarbeiter.m_nname, mitarbeiter.m_abteilung,  m_level.m_level_name 
        FROM mitarbeiter 
        INNER JOIN m_level ON m_level.m_level_id = mitarbeiter.m_lvl_ID
        WHERE m_abteilung = \"" . $_GET['abt'] . "\"";
$conn = mysql();
$res = $conn->query($sql) or die($conn->error);


$tpl = new Template("../includes/");
while($row = $res->fetch_assoc()){ //Benutzer in Abteilungsfenster hinzufügen
    $tpl->load("profileUserListItem.html");
    $pname = $row['m_vname'].' '.$row['m_nname'];
    $tpl->assign("profileName",$pname);
    $id = $row['m_ID'];
    $tpl->assign("description",getMitarbeiterLvl($id));
    $tpl->assign("profileLink","profile.php?id=".$row['m_ID']);
    $tpl->display();
}

function getMitarbeiterLvl($id){ //mitarbeiterlvl als String
    $sql = "SELECT mitarbeiter.m_lvl_ID, m_level.m_level_name as lvl FROM mitarbeiter 
            INNER JOIN m_level ON mitarbeiter.m_lvl_ID = m_level.m_level_ID
            WHERE mitarbeiter.m_ID = $id";
    $conn = mysql();
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    return $row['lvl'];
}
