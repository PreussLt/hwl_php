<?php
session_start();
include("Template.class.php");
include("src/sql.php");
include("Functions.php");
if (isset($_SESSION['bypass']) && $_SESSION['bypass'] == 1) {
    $tpl = new Template();
    $tpl->load("myprofile.html");
    $tpl->assign("navigationsLeiste", getNavigation());
    $id_is_int = isset($_GET['id']) ? (int) $_GET['id'] : null;
    $id = 0;
    if ($id_is_int) { 
        $id = $_GET['id'];
        $sql = "SELECT m_vname, m_nname, m_abteilung, m_lvl_ID, m_fuehrerschein_ID as fID FROM mitarbeiter WHERE m_ID = $id";
        $conn = mysql();
        $res = $conn->query($sql) or die($conn->error);
        $row = $res->fetch_assoc();
        
        $tpl->assign("vorname", $row['m_vname']);
        $tpl->assign("nachname", $row['m_nname']);
        $tpl->assign("fahrten", getFahrten($id));
        $tpl->assign("reservierungen", getReservierungen($id));
        $tpl->assign("abteilung", $row['m_abteilung']);
        $sql = "SELECT fsk_ID FROM fuehrerschein WHERE fs_ID = " . $row['fID'];
        $res = $conn->query($sql) or die($conn->error);
        $row = $res->fetch_assoc();
        $tpl->assign("fuehrerscheinlvl", getFuehrerscheinLvl($row['fsk_ID']));
        if ($id == $_SESSION['id']) { //ist angefragte profil = session profil?
            $logoutButton = $tpl->getContentOf("logoutButton.html");
            $tpl->assign("logoutButton", $logoutButton); //logout button hinzufügen
        } else {
            $tpl->assign("logoutButton", "");
        }
        $tpl->assign("id",$id);
    } else { // session profil
        $id = $_SESSION['id'];
        $tpl->assign("vorname", $_SESSION['vorname']);
        $tpl->assign("nachname", $_SESSION['nachname']);
        $tpl->assign("fahrten", getFahrten($id));
        $tpl->assign("reservierungen", getReservierungen($id));
        $tpl->assign("abteilung", $_SESSION['abteilung']);
        $tpl->assign("fuehrerscheinlvl", getFuehrerscheinlvl($_SESSION['fuehrerscheinLVL']));
        $logoutButton = $tpl->getContentOf("logoutButton.html");
        $tpl->assign("logoutButton", $logoutButton); //logout button hinzufügen
        $tpl->assign("id",$id);
    }



    $tpl->display();//profil darstellen
} else {
    header('Location: ./');
}

function getFuehrerscheinLvl($id) //führerschein lvl als string
{
    $sql = "SELECT fsk_bezeichnung as lvl FROM fs_klasse WHERE fsk_ID = $id";
    $conn = mysql();
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $lvl = $row['lvl'];
    return $lvl;
}


function getFahrten($id)
{
    $sql = "SELECT * FROM fahrt WHERE m_ID = $id";
    $conn = mysql();
    $res = $conn->query($sql);
    $num_rows = $res->num_rows;
    return $num_rows;
}

function getReservierungen($id)
{
    $sql = "SELECT * FROM reservierung WHERE m_id = $id";
    $conn = mysql();
    $res = $conn->query($sql);
    $num_rows = $res->num_rows;
    return $num_rows;
}

