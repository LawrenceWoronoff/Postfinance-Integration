<?php

include ("init.php");

$pack = $_GET["pack"];

//echo "Maintenance en cours";
//exit();

$referrer= $_SERVER['HTTP_REFERER'];


$package = DB::queryFirstRow("SELECT * FROM packs WHERE ID=%i", $pack);



$prix = $package["Prix"];
$prixspecial = $package["PrixSpecial"];

if ( $prixspecial > 0 ) {
	$prixfini = $prixspecial;
} else {
	$prixfini = $prix;
}


DB::insert('commandes', array(
	'Timbre' => time(),
	'Prestation' => $package["Article"],
	'Prix' => $prixfini,
	'Qte' => 1,
	'Session' => session_id(),
	
	'Pack' => $package["ID"]
));



header("location:$referrer");

?>