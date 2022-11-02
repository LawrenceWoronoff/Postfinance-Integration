<?php
include ("init.php");
session_start();
header( 'content-type: text/html; charset=utf-8' );

//require_once "command_factory.inc";



//mail("info@cyberiade.ch","payment_accept","test 2015" . $_REQUEST["SHASIGN"]  . $PostFinance->check_SHA_out( $_REQUEST , $_REQUEST["SHASIGN"]) );

$_REQUEST["orderID"]="201510070019-1444251619";
$_REQUEST["currency"]="CHF";
$_REQUEST["amount"]="1";
$_REQUEST["PM"]="CreditCard";
$_REQUEST["ACCEPTANCE"]="302115";
$_REQUEST["STATUS"]="5";
$_REQUEST["CARDNO"]="XXXXXXXXXXXX2336";
$_REQUEST["ED"]="0817";
$_REQUEST["CN"]="Eric Ecoffey";
$_REQUEST["TRXDATE"]="10/05/15";
$_REQUEST["PAYID"]="1621953349";
$_REQUEST["NCERROR"]="0";
$_REQUEST["BRAND"]="VISA";
$_REQUEST["IPCTY"]="CH";
$_REQUEST["CCCTY"]="CH";
$_REQUEST["ECI"]="5";
$_REQUEST["CVCCheck"]="NO";
$_REQUEST["AAVCheck"]="NO";
$_REQUEST["VC"]="NO";
$_REQUEST["IP"]="212.147.19.6";
$_REQUEST["SHASIGN"]="5ADA1FF5B63A914B5BCCCB361EC68D3EE17CA508";


	//require_once "postfinance.inc";

	//$PostFinance = new PostFinance;
    $top="";

	foreach( $_REQUEST as $K=>$I) {
	    $top.= $K . "=>" . $I ."\n";
	}
	mail("info@cyberiade.ch","payment_accept 3", $top );

	DB::update('commandes', array(
	'CARDNO' => $_REQUEST["CARDNO"],
	'PAYID' => $_REQUEST["PAYID"]
	), "payReference=%s",  $_REQUEST["orderID"] );

    db::debugMode();
	$elements = DB::query("SELECT * FROM commandes
	    INNER JOIN packs ON ( packs.id = commandes.Pack )
	    INNER JOIN prestations ON ( packs.PrestationID = prestations.ID )
		WHERE payReference=%s AND PAYID<>''", $_REQUEST["orderID"]  );

    $IP = $_REQUEST["IP"];
	$price = $_REQUEST["amount"];
	$nom   = $elements[0]["nom"];
	$prenom= $elements[0]["prenom"];
	$email= $elements[0]["email"];
	$adresse= $elements[0]["adresse"];
	$cp= $elements[0]["cp"];
	$localite= $elements[0]["localite"];
	$telephone= $elements[0]["telephone"];

	$files = array();
	foreach($elements as $labels )
	{




	$BC = $labels["payReference"] . "-" . $labels["id"] ;
	//$tableuxMail .= "<tr><td>{$labels[$i]['BC']}</td><td>{$labels[$i]["label"]}</td></tr>";
	$completeFile = realpath("../boncommande")."/" . $BC  .  ".pdf";
	$files[] = $completeFile;
  	require_once "lib/php/php_to_pdf/phpToPDF.php";
	$tableuxMail.= $BC . " " . $labels["Libelle"] . " " . $labels["Article"] . " ( CHF " . $labels["Article"] . ")";
	$PTP = new phpToPDF();
	$PTP->AddPage();


	$libelle = str_replace( "'","-", $labels["Libelle"]);
	$libelle = str_replace( " ","-", $labels["Libelle"]);
	$libelle =  utf8_decode($labels["Libelle"]) . " "  .  $labels["Article"];



	            $PTP->Image("./images/Voucher2015F.png", 0, 0, 210, 297);
	            $PTP->SetTextColor(90, 90, 90);
	            $PTP->SetFont("helvetica", "", 14);
	            $PTP->Text(95, 34, "N° bon");
	            $PTP->Text(135, 34, $BC);
	            $PTP->Text(95, 51, "Date");
	            $PTP->Text(135, 51, date("d.m.Y"));
	            $PTP->SetFont("helvetica", "", 16);
	            $PTP->Text(15, 241.5, $libelle);
	           $PTP->SetFont("helvetica", "", 13);

	            if ($labels["Personnes"]>1) { $plu_personnes="s"; } else { $plu_personnes=''; }

	            $PTP->Text(100, 65, "Valable pour " . $labels["Personnes"] . " personne$plu_personnes - 1 an");


	            $PTP->Output($completeFile, "F");
















	}
	$tableuxMail .= "</table>";

	require_once "lib/php/mail.inc";
	$Em = new email;


	//$files[] = $completeFile;


	$Em->mail_item(array("reply" => "thaistyle@massagemisso.ch"), array("addr" => $Email, "objet" => "Misso - Bon commande", "msg" => "Bonjour, <br /><br />Veuillez trouver en pièce jointe le ou les bons de commande pour les massages que vous avez commandés.<br /><br />"), $files);




//	$MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $nom<br />Prénom : $prenom<br />Email : $email<br />Code postal : $cp<br />Adresse : $adresse<br />Ville : $localite<br />Téléphone : $telephone<br /><br />a payé CHF $price.-<br /><br />Type de paiement : Postfinance<br />OrderID : " . $_REQUEST["orderID"] . "<br />Prix : $price CHF<br />IP : $IP<br /><br />pour les produits suivants : <br /><br />$tableuxMail<br />";
//	$Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "thaistyle@massagemisso.ch", "objet" => "Commande de $prenom $nom", "msg" => $MSG), $files);


	$MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $nom<br />Prénom : $prenom<br />Email : $email<br />Code postal : $cp<br />Adresse : $adresse<br />Ville : $localite<br />Téléphone : $telephone<br /><br />a payé CHF $price.-<br /><br />Type de paiement : Postfinance<br />OrderID : " . $_REQUEST["orderID"] . "<br />Prix : $price CHF<br />IP : $IP<br /><br />pour les produits suivants : <br /><br />$tableuxMail<br />";
	$Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "info@cyberiade.ch", "objet" => "Commande de $prenom $nom", "msg" => $MSG), $files);

	$r = true;



	//if ($PostFinance->check_SHA_out( $_REQUEST , $_REQUEST["SHASIGN"] ) === true)
	//{




		//$CF = new CommandFacoty;
		//$CF->finaliseAfterPaid2($_REQUEST);
	//	mail("info@cyberiade.ch","payment_accept 2",$_REQUEST);
	//}




?>