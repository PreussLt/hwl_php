<?php
include("sql.php");
$uploadDir = '../pics/';
$response = array(
    'status' => 0,
    'message' => 'Noch nicht unterstützt.',
    'error'   => 'unbekannter Fehler',
    'receivedData' => json_encode($_POST),
    'fahrzeugID' => -1
);


if (
    isset($_POST['kennzeichen'])        &&
    isset($_POST['f_hersteller'])       &&
    isset($_POST['f_model'])            &&
    isset($_POST['f_type'])             &&
    isset($_POST['f_kilometerstand'])   &&
    isset($_POST['f_karroserie_form'])  &&
    isset($_POST['f_farbe'])            &&
    isset($_POST['fskID'])              &&
    isset($_POST['f_mitarbeiter_level'])
) {



    try {
        $kennzeichen = $_POST['kennzeichen'];
        $hersteller = $_POST['f_hersteller'];
        $model = $_POST['f_model'];
        $type = $_POST['f_type'];
        $kilometerstand = $_POST['f_kilometerstand'];
        $karroserie = $_POST['f_karroserie_form'];
        $farbe = $_POST['f_farbe'];
        $mitarbeiterLVL = $_POST['f_mitarbeiter_level'];
        $fskID = $_POST['fskID'];
        $date = "2020-12-02";

        $sql = "INSERT INTO `f_kennzeichen`(`kennzeichen`) VALUES(?);";
        $conn = mysql();
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $kennzeichen);
            $stmt->execute();
            $kennzeichenID = $stmt->insert_id;

            $sql = "INSERT INTO fahrzeug(fsk_ID,f_hersteller,f_typ,f_model,f_kaufdatum,f_karroserie_ID,f_farbe,m_level_ID,kennzeichen_ID)
                    VALUES(?,?,?,?,?,?,?,?,?);";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("issssisii", $fskID, $hersteller, $type, $model, $date, $karroserie, $farbe, $mitarbeiterLVL, $kennzeichenID);
                $stmt->execute(); //fahrzeug hinzufügen
                $f_id = $stmt->insert_id;
                if(!empty($_FILES["file"]["name"])){ 
                    $filename = $_FILES["file"]["name"];
                    $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
                    $file_ext = substr($filename, strripos($filename, '.')); // get file name
                    $filesize = $_FILES["file"]["size"];
                    $allowed_file_types = array('.jpg');

                    $newfilename = $f_id . $file_ext;
                    if(in_array($file_ext, $allowed_file_types)){
                        if(!move_uploaded_file($_FILES["file"]["tmp_name"],$uploadDir . $newfilename)){ //datei hochladen
                            $response['message'] = 'Fehler beim upload!';
                            $response['status'] = 0;
                        }
                    }else{
                        $response['message'] = 'Falscher datei typ';
                        $response['status'] = 0;
                    }
                }else{
                    $response['message'] = 'Bild leer';
                    $response['status'] = 0;
                }
                $sql = "INSERT INTO f_kmstand(f_ID,fkm_wert) VALUES (?,?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ii", $f_id, $kilometerstand);
                    $stmt->execute(); //kilometerstand hinzufügen
                    $response['status'] = 1;
                    $response['message'] = "Fahrzeug erfolgreich eingefügt!";
                    $response['error'] = $conn->error;
                    $response['fahrzeugID'] = $f_id;
                } else {
                    $response['message'] = $conn->error;
                    $response['error'] = $conn->errno;
                    $response['status'] = 0;
                    $response['fahrzeugID'] = -1;
                }
            } else {
                $response['message'] = $conn->error;
                $response['error'] = $conn->errno;
                $response['status'] = 0;
                $response['fahrzeugID'] = -1;
            }
        } else {
            $response['message'] = $conn->error;
            $response['error'] = $conn->errno;
            $response['status'] = 0;
            $response['fahrzeugID'] = -1;
        }
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        $response['error'] = $e->getCode();
        $response['status'] = 0;
        $response['fahrzeugID'] = -1;
    }
} else {
    $response['message'] = "Falsche Parameter";
    $response['error'] = 123;
    $response['status'] = 0;
    $response['fahrzeugID'] = -1;
}

echo json_encode($response); //array als json zurückgeben
