<?php
include "inc_top_admin.php";

$id                 = $_POST["id"];
$massageid          = $_POST["massageid"];
$article            = $_POST["article"];
$prix               = $_POST["prix"];
$prixspecial        = $_POST["prixspecial"];


//DB::debugMode();
DB::update('packs', array(
'Article'       => "$article",
'Prix'         => "$prix",
'PrixSpecial'         => "$prixspecial"
), "ID=%i", $id );


header("location:packsListe.php?id=" .$massageid  );
exit();
?>