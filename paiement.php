<?php
	session_start();
	$id= session_id();
	
	include ("postfinance.inc");
	include_once ("lib/php/db/db.class.php");
	include_once ("connect.php");
	

	$PostFinance = new PostFinance;


	$langue 	= $_POST["language"];
	$name 		= $_POST["name"] . " " . $_POST["prenom"];
	$nom		= $_POST["name"];
	$prenom		= $_POST["prenom"];
	$addr 		= $_POST["addr"];
	$pays 		= $_POST["pays"];
	$town		= $_POST["town"];
	$zip		= $_POST["zip"];
	$email		= $_POST["email"];
	$tel		= $_POST["tel"];
	$total		= $_POST["total"];


	DB::insert('commande', array(
  	'id' => 0, // auto incrementing column
  	"session" => 0 ,
  	"state" => "a payer",
  	"price" => "$total",
  	"datea" => date("Y-m-d"),
  	"clientname" => "$nom",
 	"clientprenom" => "$prenom",
 	"email" => "$email",
 	"addr" => "$addr",
 	"zip" => "$zip",
 	"town" => "$town",
 	"tel" => "$tel",
	"IP" => "1234",
	"paymenttype" => "postfinance"
	));
 
	$commande_id = DB::insertId();


	DB::update('commande', array(
  	'session' => "10015" . $commande_id 
  	), "id=%i", "$commande_id");


	$CommandFacoty= (object) array(
	'Price' 	=> "$total", 
	'Lang' 		=> "$langue",
	'Name' 		=> "$name",
	'Addr' 		=> "$addr",
	'Country'	=> "$pays",
	'Town' 		=> "$town",
	'Zip' 		=> "$zip",
	'Ref' 		=> "10015" . $commande_id,
	'Email' 	=> "$email",
	'Total' 	=> "$total",
	'Tel' 		=> "$tel"

	);

	$content = $PostFinance->makeFormAutoSubmit($CommandFacoty);
	echo $content;
	?>