<?php
session_start();

include '../lib/php/mysql.class.php';
include '../lib/php/config.php';
//DB::debugMode();

/*`cours`
$id                 = $_POST["id"];
$massageid          = $_POST["massageid"];
$article            = $_POST["article"];
$prix               = $_POST["prix"];
$prixspecial        = $_POST["prixspecial"];

 */

$article            = $_POST["article"];
$prix               = $_POST["prix"];
$id                 = $_POST["id"];
$vide="";

//DB::debugMode();

if ($article!='') {
	DB::insert('packs', array(
		'Article'       => "$article",
		'Prix'       => "$prix",
		'PrixSpecial'       => 0,
		'PrestationID'       => $id

	));
}
header("location:packsListe.php?id=" .$id );
?>