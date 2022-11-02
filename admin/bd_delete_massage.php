<?php
include "inc_top_admin.php";

$id = $_GET["id"];


DB::delete('prestations', "ID=%i",  "$id" );


header("location:massagesListe.php");
exit();
?>