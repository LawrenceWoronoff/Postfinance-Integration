<?php
include ("init.php");
session_start();
header( 'content-type: text/html; charset=utf-8' );


$email = $_POST["email"] ;
$nom = $_POST["nom"] ;
$date = $_POST["date"] ;
$heure = $_POST["heure"] ;
$prestation = $_POST["prestation"] ;






	$MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $nom<br />Email : $email<br />
	souhaite un rendez-vous le $date à $heure pour la prestation<br />
	$prestation";


	$Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "info@cyberiade.ch", "objet" => "Commande de $prenom $nom", "msg" => $MSG), $files);




?>