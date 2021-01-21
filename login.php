<?php session_start();
  include './src/sql.php';

 ?>
<!DOCTYPE html>
<html lang="de" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css">
  </head>
  <body>
    <div class="login-page">
  <div class="form">
    <?php
      $pw_err = $nm_err = "";
      $password = $username = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //Überprüfen ob Benutzername gesetzt ist
      if(empty(trim($_POST["nm"]))){
        $nm_err = "Benutzername eingeben!";
        echo "<script>alert('$nm_err');</script>";
      }else{
        $username = trim($_POST["nm"]);
      }//END empty(trim($_POST["nm"]))

      //Überprüfen ob Passwort gesetzt ist
      if(empty(trim($_POST["pw"]))){
        $pw_err = "Passwort eingeben!";
        echo "<script>alert('$pw_err');</script>";
      }else{
        $password = trim($_POST["pw"]);
      }//END empty(trim($_POST["pw"]))

      //Überprüfen ob Fehler aufgetreten ist
      if(empty($pw_err) && empty($nm_err)){
        checkInput($username,$password);
      }
    }

     ?>
    <form class="login-form" action="./login.php" method="Post">
            <input name="nm" type="text" placeholder="Benutzername" autocomplete="off"/>
            <input name="pw" type="password" placeholder="Password"/>
          <input class="btn" style="background-color: #424242;color: white;" type="submit" name="submit" value="Login"> <br><br>
            <a href="./"> <== Home </a>
    </form>
  </div>
</div>
  </body>
</html>

<?php
function checkInput($name,$pw){
  global $pw_err;
  global $nm_err;
    $conn = mysql();

    // Überprüfe ob der Benutzername existiert
    $sql = "SELECT * FROM nutzer WHERE n_nutzername='$name'";
    //checkStatment($sql);
    $res = $conn->query($sql);
    $numrows = mysqli_num_rows($res);

    if ($numrows == 0) {
      // Nuter wurde nicht gefunden
      $nm_err = "Nutzer nicht gefunden!";
      echo "<script>alert('$nm_err');</script>";
    }else {
      // Nutzer wurde Gefunden
      $res = $res->fetch_assoc();

      if (password_verify($pw, $res["n_pw"])) {
        // Passwort Überprüfen

        // Passwort TRUE
        // Session Konfigurieren
        $_SESSION["bypass"] = 1;
        $_SESSION["admin"] = $res["n_admin"];

        //Mitarbeiter Name Hereusfinden
        $m_id =  $res["n_m_id"];
        $_SESSION["m_id"] = $m_id;
        $mitarbeiter =  getMitarbeiterName($m_id);
        $_SESSION["Name"] = $mitarbeiter;



        //weiterleitung
        header("location: ./");
      }else {
        // Passwort Falsch
        $pw_err = "Passwort falsch!";
        echo "<script>alert('$pw_err');</script>";
      }// END  (password_verify($pw, $res["n_pw"])

    }// End ($numrows == 0)

}

function getMitarbeiterName($m_id){
    $conn = mysql();
    // finden Den Namen des Mitarbeiter Heraus
    $sql = "SELECT * FROM mitarbeiter WHERE m_id=$m_id";
    if ($conn->query($sql)->fetch_assoc() == TRUE) {
      $res = $conn->query($sql)->fetch_assoc();
      $_SESSION['id'] = $m_id;
      $_SESSION['vorname'] = $res["m_vname"];
      $_SESSION['nachname'] = $res["m_nname"];
      $_SESSION['abteilung'] = $res["m_abteilung"];
      $_SESSION['fuehrerscheinLVL'] = getFuehrerscheinLvl($conn,$res["m_fuehrerschein_ID"]);
      $_SESSION['m_level'] = $res["m_lvl_ID"];
      $_SESSION['fs_gueltigkeit'] = getFuehrerscheinDatum($conn,$res["m_fuehrerschein_ID"]);

      $mitarbeiter = $res["m_vname"]." ".$res["m_nname"];
    }else {
      $mitarbeiter = "Fehler";
      echo "Fehler: ".$sql;
      die();
    }

    return $mitarbeiter;
}

function getFuehrerscheinLvl($conn,$fs_ID){
  $sql = "SELECT fsk_ID FROM fuehrerschein WHERE fs_ID = $fs_ID";
  if($conn->query($sql)->fetch_assoc() == TRUE){
    $res = $conn->query($sql)->fetch_assoc();
    return $res['fsk_ID'];
  }else{
    echo $sql;
  }
}

function getFuehrerscheinDatum($conn,$fs_ID){
  $sql = "SELECT fs_gueltigkeit FROM fuehrerschein WHERE fs_ID = $fs_ID";
  if($conn->query($sql)->fetch_assoc() == TRUE){
    $res = $conn->query($sql)->fetch_assoc();
    return $res['fs_gueltigkeit'];
  }else{
    echo $sql;
  }
}

 ?>
