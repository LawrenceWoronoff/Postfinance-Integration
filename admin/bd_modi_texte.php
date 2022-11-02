<?php
include "inc_top_admin.php";

$id         = $_POST["id"];
$titre      = $_POST["titre"];
$texte      = $_POST["texte"];



//DB::debugMode();
DB::update('textes', array(
'Titre'       => "$titre",
'Texte'       => "$texte",
'Timbre'      => time()
), "ID=%i", $id );


header("location:textesListe.php");
exit();
?>