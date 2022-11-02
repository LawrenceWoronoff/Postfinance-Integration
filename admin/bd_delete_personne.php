<?php
include "inc_top_admin.php";

$id = $_GET["id"];
$origine = $_GET["origine"];

DB::delete('personne', "id=%i",  "$id" );


if ($origine==3 ) {header("location:comiteListe.php");}
if ($origine==2 ) {header("location:enseignantsListe.php");}
if ($origine==1 ) {header("location:paerticipantsListe.php");}
?>