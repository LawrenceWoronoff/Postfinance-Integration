<?php
include "inc_top_admin.php";

$id         = $_POST["id"];
$libelle    = $_POST["libelle"];
$intro      = $_POST["intro"];
$texte      = $_POST["texte"];
$categorie  = $_POST["categorie"];
$ordre      = $_POST["ordre"];
$personnes  = $_POST["personnes"];

//DB::debugMode();
DB::update('prestations', array(
'Libelle'       => "$libelle",
'Intro'         => "$intro",
'Texte'         => "$texte",
'Categorie'     => "$categorie",
'Personnes'     => "$personnes",
'Ordre'         => "$ordre"
), "ID=%i", $id );


header("location:massagesListe.php");
exit();
?>