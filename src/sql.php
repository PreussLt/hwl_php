<?php
  function mysql(){
    $servername = "localhost";
    $user = "Noah";
    $pw = "Hase123!";
    $db = "fahrzeug_verwaltung";


    $conn = new mysqli($servername, $user, $pw, $db);
    if ($conn->connect_errno) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }else {
      return $conn;
    }

  }

  // Überprüft ob Abfrage Statments richtig sind !!! NICHT FÜR UPDATE,INSERT
  function checkStatment($sql){
      $conn = mysql();
      if ($conn->query($sql)==TRUE) {
        echo "Erfolgreich";
      }else {
        echo "Das Statment hat einen Fahler: ".$conn->error."  <br>SQL: ".$sql;
      }
  }


 ?>
