<?php
include("sql.php");
include("../Template.class.php");

function getKennzeichenOptions($id){ //kennzeichen dropdown items
    $sql = "SELECT kennzeichen_ID as ID, kennzeichen FROM f_kennzeichen;";
    $conn = mysql();
    $res = $conn->query($sql) or die($conn->error);
    $kennzeichen = [];
    while($row = $res->fetch_assoc()){
        $kennzeichen[] = $row;
    }

    $html = "";
    foreach($kennzeichen as $kz){
        if($kz['ID'] == $id){
            $html .= "<option value='".$kz['ID']."' selected>".$kz['kennzeichen']."</option>\n";
        }else{
            $html .= "<option value='".$kz['ID']."'>".$kz['kennzeichen']."</option>\n";
        }
    }

    return $html;
}

if(isset($_GET['id']) && !empty($_GET['id'])){
    $datetime = new DateTime('tomorrow');
    $tpl = new Template("../includes/");
    $tpl->load('fahrzeug_reservieren.html');
    $tpl->assign("startDatum",$datetime->format('Y-m-d'));
    $datetime->modify('+1 day');
    $tpl->assign("endDatum",$datetime->format('Y-m-d'));
    $tpl->assign("kennzeichenOptions",getKennzeichenOptions($_GET['id']));
    $tpl->assign("actionLink","src/fahrzeugReservierenRequestHandler.php");
    $tpl->display();
}else{
    header("Location: ./"); // weiterleitung zur home seite wenn kein Fahrzeug ausgewählt ist,
}