<?php
include "inc_top_admin.php";

$objetID = $_GET["objetID"];


DB::update('fonds', array(

'image'     => ""


),  "id=%i", $objetID );

header("location:bgListe.php");
exit();
?>