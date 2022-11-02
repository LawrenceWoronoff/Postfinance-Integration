<?php
include ("init.php");
session_start();
header( 'content-type: text/html; charset=utf-8' );


$email = $_POST["email"] ;
$telephone = $_POST["telephone"] ;
$nom = $_POST["nom"] ;
$date = $_POST["date"] ;
$heure = $_POST["heure"] ;
$prestation = $_POST["prestation"] ;

$prenom = $_POST["prenom"] ;
$message= $_POST["message"] ;
$file[]="";
require_once "lib/php/mail.inc";
$Em = new email;


if ($prestation!='') {
	$MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $nom<br />
	Email : $email<br />
	Téléphone : $telephone<br />
	souhaite un rendez-vous le<b> $date à $heure </b>pour la prestation<br />
	$prestation";

	$Em->mail_item(array("reply" => $email), array("addr" => "info@cyberiade.ch", "objet" => "Reservation de  $nom", "msg" => $MSG), $file);
	$Em->mail_item(array("reply" => $email), array("addr" => "thaistyle@massagemisso.ch", "objet" => "Reservation de  $nom", "msg" => $MSG), $file);
}

if ($message!='') {
    $MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $nom
    <br />Email : $email
    <br />Téléphone : $telephone
    <br /><br />
    A laissé un message<br />
    $message";

 	$Em->mail_item(array("reply" => $email), array("addr" => "info@cyberiade.ch", "objet" => "Message de $prenom  $nom", "msg" => $MSG), $file);
 	$Em->mail_item(array("reply" => $email), array("addr" => "thaistyle@massagemisso.ch", "objet" => "Message de $prenom  $nom", "msg" => $MSG), $file);


}

header("location:site.php");


?>