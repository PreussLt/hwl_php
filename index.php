<?php session_start();
include("Template.class.php");
include("Functions.php");

$tpl = new Template("includes/");
$tpl->load("index.html");

$tpl->assign("navigationsLeiste",getNavigation());
$tpl->display();

?>