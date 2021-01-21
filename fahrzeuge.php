<?php session_start(); 
include("Template.class.php");
include("Functions.php");

//template laden
$tpl = new Template();
$tpl->load("fahrzeuge.html");
$tpl->assign("menue",getFahrzeugMenue());
$tpl->assign("navigationsLeiste",getNavigation());
$tpl->display();

?>