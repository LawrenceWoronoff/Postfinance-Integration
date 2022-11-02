<?php
include ("init.php");
session_start();
header( 'content-type: text/html; charset=utf-8' );


if($_POST)
{
	require('constant.php');

	$user_nom      		= htmlspecialchars(stripslashes(trim($_POST['nom'])));
	$user_prestation    = htmlspecialchars(stripslashes(trim($_POST['prestation'])));
	$user_date      	= htmlspecialchars(stripslashes(trim($_POST['date'])));
	$user_heure      	= htmlspecialchars(stripslashes(trim($_POST['heure'])));
	$user_email     	= htmlspecialchars(stripslashes(trim($_POST['email'])));
	$user_telephone     = htmlspecialchars(stripslashes(trim($_POST['telephone'])));
	$file[]="";

	if(empty($user_nom)) {
		$empty[] = "<b>votre nom et prénom</b>";		
	}
	if(empty($user_email)) {
		$empty[] = "<b>votre adresse mail</b>";
	}
	if(empty($user_telephone)) {
		$empty[] = "<b>votre numéro de téléphone</b>";
	}
	if(empty($user_date)) {
		$empty[] = "<b>date</b>";
	}
	if(empty($user_heure)) {
		$empty[] = "<b>heure</b>";
	}
	if(!empty($empty)) {
		$output = json_encode(array('type'=>'error', 'text' => implode(", ",$empty) . ' Requis!'));
		die($output);
	}
	if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
		$output = json_encode(array('type'=>'error', 'text' => '<b>'.$user_email.'</b>  Email non valide, merci de le corriger.'));
		die($output);
	}
	
	//reCAPTCHA validation
	/*if (isset($_POST['g-recaptcha-response'])) {
		
		require('component/recaptcha/src/autoload.php');		
		
		$recaptcha = new \ReCaptcha\ReCaptcha(SECRET_KEY, new \ReCaptcha\RequestMethod\SocketPost());

		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		$resp = isSuccess();

		if (!$resp->isSuccess()) {
			$output = json_encode(array('type'=>'error', 'text' => '<b>Captcha</b> Validation requise!'));
			die($output);				
		}	
	}*/
	


require_once "./lib/php/mail.inc";

$Em = new email;


	$MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $user_nom<br />
	Email : $user_email<br />
	Téléphone : $user_telephone<br />
	souhaite un rendez-vous le<b> $user_date à $user_heure </b>pour la prestation<br />
	$user_prestation";
	

	$Em->mail_item(array("reply" => $user_email), array("addr" => "info@cyberiade.ch", "objet" => "Reservation de  $user_nom", "msg" => $MSG), $file);
	$Em->mail_item(array("reply" => $email), array("addr" => "thaistyle@massagemisso.ch", "objet" => "Reservation de  $nom", "msg" => $MSG), $file);

	$output = json_encode(array('type'=>'message', 'text' => 'Bonjour '. $user_nom .', merci de votre message. Nous allons prendre contact avec vous.'));

	die($output);

}

$output = json_encode(array('type'=>'message', 'text' => 'Rien ne va.'));

	die($output);
?>