<?php
include("sql.php");
include("../Template.class.php");

function getFahList($id){
    $fahrten = array();
    $sql="SELECT * FROM fahrt WHERE m_ID = $id";
    $conn = mysql();
    $res = $conn->query($sql) or die($conn->error);
    while($row = $res->fetch_assoc()){
        $fahrten[] = $row;
    }
    return $fahrten;
}

    
    $html = "";
    $fahrten = getFahList($_GET['id']);
    if(count($fahrten) > 0){
        $htmlText = "<span class='keineReservierung'>not supported yet</span>";
    }else{
        $htmlText = "<span class='keineReservierung'>Keine Fahrten vorhanden.</span>";
    }

    echo $htmlText;
