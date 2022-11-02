<?php
include "inc_top_admin.php";

$id         = $_POST["id"];
$libelle    = $_POST["libelle"];
$intro      = $_POST["intro"];
$texte      = $_POST["texte"];
$categorie  = $_POST["categorie"];
$ordre      = $_POST["ordre"];


//DB::debugMode();
DB::update('articles', array(
'Libelle'       => "$libelle",
'Intro'         => "$intro",
'Texte'         => "$texte",
'Categorie'     => "$categorie",
'Ordre'         => "$ordre"
), "ID=%i", $id );


header("location:articlesListe.php");
exit();
?>