<?php

function getNavigation()
{
  if (isset($_SESSION["bypass"])) {
    // User ist eingeloggt

    if ($_SESSION["admin"] == 0) {
      // User ist ein Admin
      $replace = '
            <a href="./fahrzeuge.php"><button type="" name="button">Fahrzeuge</button></a>
            <a href="./profile.php"><button type="" name="button">Mein Profil</button></a>
            ';
    } elseif ($_SESSION["admin"] == 1) {
      // User ist ein Admin

      $replace = '
            <a href="./fahrzeuge.php"><button type="" name="button">Fahrzeuge</button></a>
            <a href="./mitarbeiter.php"><button type="" name="button">Mitarbeiter</button></a>
            <a href="./profile.php"><button type="" name="button">Mein Profil</button></a>
            ';
    } //END ($_SESSION["admin"]==0)

  } else {
    // User ist nicht eingeloggt
    $replace = '
        <a href="./login.php"><button type="" name="button">Login</button></a>
        ';
  } // Ende if($_SESSION["bypass"] == TRUE)

  return $replace;
}

function getFahrzeugMenue()
{
  if ($_SESSION["bypass"] == 1) {
    // Ein Nutzer ist eingeloggt
    if ($_SESSION["admin"] == 1) {
      $replace = '
            <div class="menue" style="padding: 10px;">
              <button onclick="fahrzeugeClicked()" >Fahrzeug Liste</button>
              <button onclick="fahrzeugAnlegenClicked()" >Fahrzeug anlegen</button>
            </div>
            ';
    } else {
      $replace = '
            <div class="menue" style="padding: 10px;">
              <button onclick="fahrzeugeClicked()" >Fahrzeug Liste</button>
            </div>
            ';
    }
  } else {
    header('Location: ./');
  }
  return $replace;
}
