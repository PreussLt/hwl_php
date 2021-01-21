<?php
    include("sql.php");
    session_start();
    $response = array(
        'status' => 0,
        'message' => 'Noch nicht unterstützt.',
        'error'   => 'unbekannter Fehler',
        'receivedData' => json_encode($_POST)
    );
    try{
    if(
        isset($_POST['kennzeichen']) &&
        isset($_POST['datum_start']) &&
        isset($_POST['datum_end'])   &&
        isset($_POST['Grund'])
    ){
        $kennzeichenID = $_POST['kennzeichen'];
        $datum_start = $_POST['datum_start'];
        $datum_end = $_POST['datum_end'];
        $grund = $_POST['Grund'];

        $sql = "SELECT f_ID FROM fahrzeug
                WHERE kennzeichen_ID = $kennzeichenID;";
        
        $conn = mysql();
        if($res = $conn->query($sql)){
            $row = $res->fetch_assoc();
            $f_ID = $row['f_ID'];

            if(checkIfAvailable($f_ID,$datum_start)){ //ob Fahrzeug in diesem Zeitpunkt schon reserviert
                $sql = "INSERT INTO reservierung(f_ID, m_ID, res_datum_start, res_datum_end, res_zweck)
                        VALUES (?,?,?,?,?)";
                if($stmt = $conn->prepare($sql)){
                    $stmt->bind_param('iisss',$f_ID,$_SESSION['id'],$datum_start,$datum_end,$grund);
                    $stmt->execute(); //Reservierung hinzufügen
                    $response['status'] = 1;
                    $response['message'] = 'erfolgreich';
                    $response['error'] = 'none';
                }else{
                    $response['message'] = $conn->error;
                    $response['error'] = $conn->errno;
                }
            }else{
                $response['message'] = "Fehler! Fahrzeug innerhalb dieses Zeitpunkts schon reserviert.";
                $response['error'] = "inuse";
            }
        }else{
            $response['message'] = $conn->error;
            $response['error'] = $conn->errno;
        }
    }
}catch(Exception $e){
    $response['message'] = $e -> getMessage();
    $response['error'] = $e -> getCode();
}

function checkIfAvailable($id,$startDatum){ //ob Fahrzeug in diesem Zeitpunkt schon reserviert
    $compareDate = new DateTime($startDatum);
    $a = true;
    $sql = "SELECT res_datum_start as dstart, res_datum_end as dend
            FROM reservierung
            WHERE f_ID = $id";
    $conn = mysql();
    if($res = $conn->query($sql)){
        if($res->num_rows > 0){
            
            while($row = $res->fetch_assoc()){
                $dstart = new DateTime($row['dstart']);
                $dend = new DateTime($row['dend']);
                if($compareDate > $dstart){
                    if($compareDate < $dend){
                        $a = false;
                    }
                }
            }
        }else{
            $a = true;
        }
    }

    return $a;
}


    echo json_encode($response); // array als json zurückgeben
