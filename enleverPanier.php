<?php
include ("init.php");
$pack = $_GET["pack"];

$referrer= $_SERVER['HTTP_REFERER'];
//DB::debugMode();


DB::delete('commandes', "Pack=%i", $pack );

header("location:$referrer");
?>