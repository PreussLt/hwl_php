<?php
session_start();
include("Template.class.php");
include("Functions.php");
//header
$tpl = new Template();
$tpl->load("mitarbeiter.html");
$tpl->assign("navigationsLeiste",getNavigation());
$tpl->display();
?>