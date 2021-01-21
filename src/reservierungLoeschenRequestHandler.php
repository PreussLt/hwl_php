<?php 
error_reporting(0); // fehler werden nicht als text ausgegeben -> parseError vermieden.
ini_set('display_errors', 0);// Durch eine fehler ausgabe würde rückgabe nichtmehr json format
include 'sql.php';

$response = array(
    'message' => 'Löschen fehlgeschlagen',
    'successfull' => 0
); 

if(isset($_POST['res_id']) && !empty($_POST['res_id']) && isset($_POST['m_id']) && !empty($_POST['m_id'])){
    $res_id = $_POST['res_id'];
    $m_id = $_POST['m_id'];
    $sql = "DELETE FROM reservierung WHERE res_ID = $res_id AND m_ID = $m_id";
    $conn = mysql();
    if($res = $conn->query($sql)){ // reservierung löschen
        $response['message'] = 'Löschen erfolgreich';
        $repsonse['successfull'] = 1;
    }
}

json_encode($response); // array als json zurückgeben