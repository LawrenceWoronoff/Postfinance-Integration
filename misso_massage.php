<?php
session_start();
$id= session_id();


require_once "define.inc";
include "module__language.inc";

if (!isset($_SESSION["langue"])) {
	$langue="FR";
}
else
{
	$langue=$_SESSION["langue"];

}
if ($langue=="EN") {
	define('VOTREPANIER',"Your basket contains");
}else
{
	define('VOTREPANIER',"Votre panier contient");
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	include "inc_connect.php";

	$PAGE = !empty($_GET["page"]) ? $_GET["page"] : false;

	$procut_lists = CommandFacoty::productList();

	if ((int)$PAGE > 100)
	{
		$productRef = (int) substr($PAGE, 1);
		$PAGE = (int)substr($PAGE, 0, 1);

		

		if (isset($procut_lists[$productRef]))
		{
			if (!isset($_SESSION["cart"]))
				$_SESSION["cart"] = array();
			if (!isset($_SESSION["cart"][$productRef]))
				$_SESSION["cart"][$productRef] = array("qty" => 0);
			$_SESSION["cart"][$productRef]["qty"]++;
		}
	}
	if (!empty($_REQUEST["cartdel"]) && isset($_SESSION["cart"]))
	{
		$productRef = $_REQUEST["cartdel"];
		if (isset($_SESSION["cart"][$productRef]))
		{
			$_SESSION["cart"][$productRef]["qty"]--;
			if ($_SESSION["cart"][$productRef]["qty"] == 0)
				unset($_SESSION["cart"][$productRef]);
		}
	}


	if (isset($_REQUEST["cancel_cmd"]))
		unset($_SESSION["commande_ID"]);

	if ($PAGE == "panier")
		if (isset($_SESSION["commande_ID"]))
			$PAGE = "validate_cmd";

	if ($PAGE == "validate_cmd")
		if(empty($_SESSION["cart"]))
			$PAGE = "panier";

	if ($PAGE == "paymentsend")
	{
		$CommandFacoty = new CommandFacoty;
		if ($CommandFacoty->registerBeforePayment() !== true)
			$PAGE = 1;
	}

	if ($PAGE == "paymentcancel" || $PAGE == "paymentaccept")
	{
		if (isset($_REQUEST["SHASIGN"]))
		{
			$PostFinance = new PostFinance;
			if ($PostFinance->check_SHA_out($_REQUEST, $_REQUEST["SHASIGN"]))
			{
				$CF = new CommandFacoty;
				$DATA_cmd = $CF->get($_REQUEST["orderID"]);

				$acc_canc_check = true;
				if ($PAGE == "paymentaccept")
					if ($DATA_cmd["state"] != "paid")
						$acc_canc_check = false;
				if ($PAGE == "paymentcancel")
					if ($DATA_cmd["state"] != "abort")
						$acc_canc_check = false;

				if ($acc_canc_check)
				{
					unset($_SESSION["commande_ID"]);
					unset($_SESSION["cart"]);
				}
				else
					$PAGE = 1;
			}
			else
				$PAGE = 1;
		}
		elseif (isset($_SESSION["paymentSaferPay__finalise"]) && $_SESSION["paymentSaferPay__finalise"] == true)
		{
			$CF = new CommandFacoty;
			$DATA_cmd = $CF->get($_SESSION["paymentSaferPay__orderID"]);
			unset($_SESSION["commande_ID"]);
			unset($_SESSION["cart"]);
		}
		else
			$PAGE = 1;
	}

	$titre = "";


	switch($PAGE)
	{
		default:
			$css="style_page1.css";

			$menu ="misso_page1_menu.gif";
			$cadeau = "misso_page1_cadeau.png";
			$marge = "";
			$donnez = "donnez-vous1.gif";

			$titre = "<h1>Massages traditionnels thaïs</h1>";
			$content = "<h3>Liste des prestations</h3>";




			if ($langue=='EN') {
				$cat=13;
				$menu ="EN_misso_page1_menu.gif";
				$titre = "<h1>Thai traditional massage</h1>";
				$content = "<h3>List of treatments</h3>";

				}
				else {
					$menu ="misso_page1_menu.gif";
					$titre = "<h1>Massages traditionnels thaïs</h1>";
					$content = "<h3>Liste des prestations</h3>";

				$cat = 6;
				}


			$sql ="SELECT * FROM jos_content WHERE catid=$cat AND state=1 ORDER BY ordering";
			$rs = mysql_query($sql);
			while ($rw = mysql_fetch_array($rs))
			{
				$fulltext= str_replace("'","\'", $rw["fulltext"]);
				$fulltext= str_replace(chr(10),"", $fulltext);
				$fulltext= str_replace(chr(13),"", $fulltext);

				$content.="<div class=\"box\">";
				$content.= "<img onclick=\"Misso.popText('" . $fulltext . "')\" style=\"cursor:pointer;float:right;\" src=\"images/ground/plusdetails.png\" border=\"0\">";
				$content.= "<h2>" . $rw["title"] . "</h2>";
				$content.= $rw["introtext"];
				$content.="</div>";
			}
		break;

		case 2:
			$css="style_page2.css";
			$menu ="misso_page2_menu.gif";
			$cadeau = "misso_page2_cadeau.png";
			$marge = "";
			$donnez = "donnez-vous2.gif";

			$titre= "<h1>Sélection Misso</h1>";
			//$content.= "<h3>Liste des prestations</h3>";
			$content = "";

			if ($langue == 'EN')
			{
				$cat = 14;
				$titre= "<h1>Misso Selection</h1>";
				$menu ="EN_misso_page2_menu.gif";
			}
			else
			{
				$cat = 7;
				$titre = "<h1>Sélection Misso</h1>";
				$menu = "misso_page2_menu.gif";
			}

			$sql ="SELECT * FROM jos_content WHERE catid=$cat AND state=1 ORDER BY ordering";
			$rs = mysql_query($sql);
			while ($rw = mysql_fetch_array($rs))
			{
				$fulltext= str_replace("'","\'", $rw["fulltext"]);
				$fulltext= str_replace(chr(10),"", $fulltext);
				$fulltext= str_replace(chr(13),"", $fulltext);

				$content.="<div class=\"box\">";
				$content.= "<img onclick=\"Misso.popText('" . $fulltext . "')\" style=\"cursor:pointer;float:right;\"src=\"images/ground/plusdetails.png\" border=\"0\">";
				$content.= "<h2>" . $rw["title"] . "</h2>";
				$content.= $rw["introtext"];
				$content.="</div>";
			}



		break;

		case 3:
			$css="style_page3.css";
			$menu ="misso_page3_menu.gif";
			$cadeau = "misso_page3_cadeau.png";
			$marge = "";
			$donnez = "donnez-vous3.gif";

			$titre = "<h1>The <b>Thai</b>lounge<br />Massage et cuisine thaie</h1>";
			//$content= "<h3>Liste des prestations</h3>";
			$content = "";

			if ($langue=='EN')
			{
				$cat=15;
				$titre = "<h1>The <b>Thai</b>lounge<br />Massage and thai cuisine</h1>";
				$menu ="EN_misso_page3_menu.gif";
			}
			else {
				$cat = 8;
				$titre = "<h1>The <b>Thai</b>lounge<br />Massage et cuisine thaie</h1>";
				$menu ="misso_page3_menu.gif";
			}

			$sql ="SELECT * FROM jos_content WHERE catid=$cat AND state=1 ORDER BY ordering";
			$rs = mysql_query($sql);
			while ($rw = mysql_fetch_array($rs))
			{
				$fulltext= str_replace("'","\'", $rw["fulltext"]);
				$fulltext= str_replace(chr(10),"", $fulltext);
				$fulltext= str_replace(chr(13),"", $fulltext);

				$content.="<div class=\"box1\">";
				$content.= "<h2>" . $rw["title"] . "</h2>";
				$content.= $rw["introtext"];

				//$content.= "<img onclick=\"misso.popText('" . $fulltext . "')\" style=\"cursor:pointer;float:right;\"src=\"images/ground/plusdetails.png\" border=\"0\">";
				$content.="</div>";
			}



		break;

		case 4:
			$css		= "style_page4.css";
			$cadeau 	= "misso_page3_cadeau.png";
			$marge = "<br />";
			$donnez = "donnez-vous4.gif";



			$content= "<h1>Bon cadeaux et abonnements<br />Achetez et imprimez vos bons immédiatement</h1>";


			if ($langue=='EN')
			{
				$cat=16;
				$content= "<h1>GIFT VOUCHERS AND SUBSCRIPTIONS<br />Buy and print your vouchers</h1>";
				$menu 		= "EN_misso_page4_menu.gif";
			}
			else
			{
				$cat = 9;
				$content= "<h1>Bon cadeaux et abonnements<br />Achetez et imprimez vos bons immédiatement</h1>";
				$menu 		= "misso_page4_menu.gif";
			}
			$sql ="SELECT * FROM jos_content WHERE catid=$cat AND state=1 ORDER BY ordering";
			$rs = mysql_query($sql);
			while ($rw = mysql_fetch_array($rs))
			{
				$content.="<div class=\"box1\">";
			//	$content.= "<h2>" . $rw["title"] . "</h2>";
				$content.= $rw["introtext"];
				$fulltext= str_replace("'","\'", $rw["fulltext"]);
				$fulltext= str_replace(chr(10),"", $fulltext);
				$fulltext= str_replace(chr(13),"", $fulltext);

				$content.="</div>";
			}

		break;

		case 5:
			$css="style_page4.css";
			$menu ="misso_page4_menu.gif";
			$cadeau = "misso_page4_cadeau.png";
			$marge = "<br />";
			$donnez = "donnez-vous5.gif";

			if ($langue=='EN')
			{
				$cat=16;
				$content= "<h1>GIFT VOUCHERS AND SUBSCRIPTIONS<br />Buy and print your vouchers</h1>";
				$menu ="EN_misso_page4_menu.gif";
			}
			else
			{
				$cat = 9;
				$content= "<h1>Bon cadeaux et abonnements<br />Achetez et imprimez vos bons immédiatement</h1>";
				$menu ="misso_page4_menu.gif";
			}
			$sql ="SELECT * FROM jos_content WHERE catid=$cat AND state=1 ORDER BY ordering";
			$rs = mysql_query($sql);
			while ($rw = mysql_fetch_array($rs))
			{
				$fulltext= str_replace("'","\'", $rw["fulltext"]);
				$fulltext= str_replace(chr(10),"", $fulltext);
				$fulltext= str_replace(chr(13),"", $fulltext);

				$content.="<div class=\"box1\">";
				//	$content.= "<h2>" . $rw["title"] . "</h2>";
				$content.= $rw["introtext"];

				$content.="</div>";
			}


		break;

		case 6:
			$css="style_page6.css";
			$menu ="misso_page6_menu.gif";
			$cadeau = "misso_page6_cadeau.png";
			$marge = "";

			$titre = "";
			$donnez = "donnez-vous5.gif";
			$content= "";
			if ($langue=='EN') {
				$cat=12;
			$menu ="EN_misso_page6_menu.gif";
						} else {
				$cat = 3;
			$menu ="misso_page6_menu.gif";
								}
			$sql ="SELECT * FROM jos_content WHERE catid=3 AND state=1 ORDER BY ordering";
			$rs = mysql_query($sql);
			while ($rw = mysql_fetch_array($rs))
			{
				$fulltext= str_replace("'","\'", $rw["fulltext"]);
				$fulltext= str_replace("images/stories","datas/images/stories", $fulltext);
				$fulltext= str_replace("\"","", $fulltext);
				$fulltext= str_replace(chr(10),"", $fulltext);
				$fulltext= str_replace(chr(13),"", $fulltext);


				$content.="<div class=\"box\">";
				$content.= "<img onclick=\"Misso.popText('" . $fulltext . "')\" style=\"cursor:pointer;float:right;\"src=\"images/ground/plusarticle.png\" border=\"0\">";
				$content.= $rw["introtext"];
				$content= str_replace("\"images/stories","\"datas/images/stories", $content);


				$content.="</div>";
			}

		break;

		case 7:
			$css		= "style_page4.css";
			$menu 		= "misso_page4_menu.gif";
			$cadeau 	= "misso_page3_cadeau.png";
			$marge = "<br />";
			$donnez = "donnez-vous4.gif";

			$content= "<h1>Bon cadeaux et abonnements<br />Achetez et imprimez vos bons immédiatement</h1>";


			if ($langue=='EN') { $cat = 18; } else { $cat = 18; }
			$sql ="SELECT * FROM jos_content WHERE catid=$cat AND state=1 ORDER BY ordering";
			$rs = mysql_query($sql);
			while ($rw = mysql_fetch_array($rs))
			{
				$fulltext= str_replace("'","\'", $rw["fulltext"]);
				$fulltext= str_replace(chr(10),"", $fulltext);
				$fulltext= str_replace(chr(13),"", $fulltext);

				$content.="<div class=\"box1\">";
				//	$content.= "<h2>" . $rw["title"] . "</h2>";
				$content.= $rw["introtext"];

				$content.="</div>";
			}
		break;

		case "panier":
			$css		= "style_cmd.css";
			$cadeau 	= "misso_page3_cadeau.png";
			$marge = "<br />";
			$donnez = "donnez-vous4.gif";

			$menu = $langue == 'EN' ? "EN_misso_page4_menu.gif" : "misso_page4_menu.gif";

			$content = $langue == 'EN' ? "<h1>Your items list</h1>" : "<h1>Listes des articles</h1>";
			$label = $langue == 'EN' ? "Name" : "Nom";
			$Price = $langue == 'EN' ? "Price" : "Prix";
			$qty = $langue == 'EN' ? "Quantity" : "Quantité";
			$TitleValid = $langue == 'EN' ? "Validate" : "Valider ma commande";

			if (isset($_SESSION["cart"]))
			{
				$prixTotal = 0;
				$content .= "<br /><br /><table class='cartList'><thead><tr><th>$label</th><th>$qty</th><th>$Price</th><th></th></tr></thead><tbody>";
				foreach ($_SESSION["cart"] AS $k => $v)
				{
					$content .= "<tr>";
					$content .= '<td style="text-align:left">';
					$content .= $procut_lists[$k]["label"];
					$content .= "</td>";

					$content .= "<td>";
					$content .= $v["qty"];
					$content .= "</td>";

					$content .= "<td>";
					$content .= ($v["qty"]*$procut_lists[$k]["prix"])." ".$procut_lists[$k]["monnaie"];
					$content .= "</td>";

					$content .= "<td>";
					$content .= '<a href="misso_massage.php?page=panier&cartdel='.$k.'"><img src="images/x.png" alt="" /></a>';
					$content .= "</td>";
					$content .= "</tr>";

					$prixTotal += $v["qty"]*$procut_lists[$k]["prix"];
				}
				$content .= "</<tbody></table>";

				$content .= <<<HTML
					<div>
						<a href="misso_massage.php?page=validate_cmd" onclick="window.location=this.href; return false;"><button class="mg_btn_grey" style="border:1px solid #000000;color:#000000;float:left;margin:23px 0px 0px 5px;">$TitleValid</button></a>
						<div style="float:right;margin:30px 80px 0px 0px;">TOTAL <span style="color:#F00">$prixTotal<span> CHF</div>
						<div style="clear:both;"></div>
					</div>
HTML;
			} else
				$content .= "<br /><br /> Votre panier est vide";
		break;

		case "validate_cmd":
				$css		= "style_cmd.css";
				$cadeau 	= "misso_page3_cadeau.png";
				$marge = "<br />";
				$donnez = "donnez-vous4.gif";
				$menu = $langue == 'EN' ? "EN_misso_page4_menu.gif" : "misso_page4_menu.gif";

				$content = $langue == 'EN' ? "<h1>Check your command</h1>" : "<h1>Résumé de votre commande</h1>";
				$label = $langue == 'EN' ? "Name" : "Nom";
				$Price = $langue == 'EN' ? "Price" : "Prix";
				$qty = $langue == 'EN' ? "Quantity" : "Quantité";

				if (isset($_SESSION["cart"]))
				{
					if (count($_SESSION["cart"]) > 0)
					{
						if (empty($_SESSION["commande_ID"]))
							$_SESSION["commande_ID"] = uniqid();

						$prixTotal = 0;
						$content .= "<table class='cartList' style='margin-top:0px;'><thead><tr><th>$label</th><th>$qty</th><th>$Price</th></tr></thead><tbody>";
						foreach ($_SESSION["cart"] AS $k => $v)
						{
							$content .= "<tr>";
							$content .= '<td style="text-align:left">';
							$content .= $procut_lists[$k]["label"];
							$content .= "</td>";

							$content .= "<td>";
							$content .= $v["qty"];
							$content .= "</td>";

							$content .= "<td>";
							$content .= ($v["qty"]*$procut_lists[$k]["prix"])." ".$procut_lists[$k]["monnaie"];
							$content .= "</td>";

							$prixTotal += $v["qty"]*$procut_lists[$k]["prix"];
						}
						$content .= "</<tbody></table>";

						if ($langue=='EN') { $cat = 21; } else { $cat = 18; }
						$sql ="SELECT introtext FROM jos_content WHERE catid=$cat AND state=1 ORDER BY ordering";
						$rs = mysql_query($sql);
						$CGV = "";
						while ($rw = mysql_fetch_array($rs))
							$CGV .= $rw["introtext"];
						$CGV = str_replace("'","\'", $CGV);
						$CGV = str_replace('"',"\'", $CGV);
						$CGV = str_replace(chr(10),"", $CGV);
						$CGV = str_replace(chr(13),"", $CGV);

						$paymentTitle = $langue == 'EN' ? "Thank you fill out this form." : "Merci de compléter vos données personnelles dans ce formulaire.";
						$TitleLang = $langue == 'EN' ? "Language" : "Langue";
						$TitleLastName = $langue == 'EN' ? "Lastname" : "Nom";
						$TitleName = $langue == 'EN' ? "First name" : "Prénom";
						$TitleEmail = $langue == 'EN' ? "Email" : "Email";
						$TitleZip = $langue == 'EN' ? "Zip Code" : "Code postal";
						$TitleAddr = $langue == 'EN' ? "Address" : "Adresse";
						$TitlePays = $langue == 'EN' ? "Country" : "Pays";
						$TitleVille = $langue == 'EN' ? "City" : "Ville";
						$TitlePhone = $langue == 'EN' ? "Phone" : "Téléphone";
						$CGVagree = $langue == 'EN' ? "I agree the general terms of sale" : "J'accepte les conditions générales de vente";
						$TitlePaid = $langue == 'EN' ? "Pay my order" : "Payer ma commande";
						$TitleCancel = $langue == 'EN' ? "Cancel" : "Annuler ma commande";
						$TitlePaymentType = $langue == 'EN' ? "Payment type" : "Type de paiement";

						$content .= <<<HTML
							<div>
								<div style="float:right;margin:30px 28px 0px 0px;">TOTAL <span style="color:#F00">$prixTotal<span> CHF</div>
								<div style="clear:both;"></div>
							</div>

							<h2 class="paymentFormTitle">$paymentTitle</h2>
							<form method="post" action="misso_massage.php?page=paymentsend" id="paymentForm">
								<div class="paymentForm__cf">
									<label for="paymentForm__lang">$TitleLang :</label>
									<select type="hidden" name="language" id="paymentForm__lang">
										<option value="fr_FR">Français</option>
										<option value="en_US">Anglais</option>
										<option value="it_IT">Italien</option>
										<option value="de_DE">Allemand</option>
									</select>
								</div>
								<div class="paymentForm__cf">
									<label for="paymentForm__cn">$TitleLastName<span style="color:#F00">*</span> :</label>
									<input type="text" name="name" value="" id="paymentForm__cn" style="" />
								</div>
								<div class="paymentForm__cf">
									<label for="paymentForm__cp">$TitleName<span style="color:#F00">*</span> :</label>
									<input type="text" name="prenom" value="" id="paymentForm__cp" />
								</div>
								<div class="paymentForm__cf">
									<label for="paymentForm__email">$TitleEmail<span style="color:#F00">*</span> :</label>
									<input type="text" name="email" value="" id="paymentForm__email" />
								</div>
								<div class="paymentForm__cf">
									<label for="paymentForm__zip">$TitleZip<span style="color:#F00">*</span> :</label>
									<input type="text" name="zip" value="" id="paymentForm__zip" />
								</div>
								<div class="paymentForm__cf">
									<label for="paymentForm__addr">$TitleAddr<span style="color:#F00">*</span> :</label>
									<input type="text" name="addr" value="" id="paymentForm__addr" />
								</div>
								<div class="paymentForm__cf">
									<label for="paymentForm__cty">$TitlePays<span style="color:#F00">*</span> :</label>
									<input type="text" name="pays" value="Suisse" id="paymentForm__cty" />
								</div>
								<div class="paymentForm__cf">
									<label for="paymentForm__town">$TitleVille<span style="color:#F00">*</span> :</label>
									<input type="text" name="town" value="" id="paymentForm__town" />
								</div>
								<div class="paymentForm__cf">
									<label for="paymentForm__telno">$TitlePhone<span style="color:#F00">*</span> :</label>
									<input type="text" name="tel" value="" id="paymentForm__telno" />
								</div>
								<div style="margin-top:10px;">
									<input type="checkbox" id="paymentForm__cgv" name="cgv" />
									<span onclick="Misso.popText('$CGV', {width:'600px'});" style="cursor:pointer;color:#E5BE97;text-decoration:underline;">$CGVagree</span>
								</div>
								<div id="paymentForm__cgv_infos"></div>
							</form>
							<div style="margin-top:50px">
								<div onclick="Misso.checkPayment({idform:'paymentForm'})" style="float:left;color:#000000;margin-left:120px;width:140px;" class="mg_btn_grey">$TitlePaid</div>
								<div onclick="window.location='misso_massage.php?page=panier&amp;cancel_cmd=true'" style="float:left;color:#000000;margin-left:50px;width:150px;" class="mg_btn_grey">$TitleCancel</div>
								<div style="clear:both"></div>
							</div>
HTML;
					}
					else
						$content .= "Votre panier est vide, vous ne pouvez pas passer de commande.";
				}
		break;

		case "paymentsend":

			$css		= "style_cmd.css";
			$cadeau 	= "misso_page3_cadeau.png";
			$marge = "<br />";
			$donnez = "donnez-vous4.gif";
			$menu = $langue == 'EN' ? "EN_misso_page4_menu.gif" : "misso_page4_menu.gif";

			$content = $langue == 'EN' ? "<h1>Please wait, transfer to the payment system.</h1>" : "<h1>Veuillez patienter, transfert vers le système de paiement.</h1>";


			if ($CommandFacoty->paymentType == "postfinance")
			{
				$PostFinance = new PostFinance;
				$content .= $PostFinance->makeFormAutoSubmit($CommandFacoty);
			}
			elseif ($CommandFacoty->paymentType == "saferpay")
			{
				$SaferPay = new SaferPay;
				$URL_REDIRECT = $SaferPay->redirect_SaferpayPaymentPage($CommandFacoty);
				$content .= '<script type="text/javascript">window.location.href="'.$URL_REDIRECT.'"</script>';
			}
		break;

		case "paymentaccept":
			$css		= "style_cmd.css";
			$cadeau 	= "misso_page3_cadeau.png";
			$marge = "<br />";
			$donnez = "donnez-vous4.gif";
			$menu = $langue == 'EN' ? "EN_misso_page4_menu.gif" : "misso_page4_menu.gif";

			$UserEmail = $DATA_cmd["email"];
			if ($langue == 'EN')
			{
				$content = "
					<h1>Request successful</h1>
					<div>Congratulations your order has been registered successfully, <br />
					A mail with your vouchers was sent to <em>$UserEmail</em></div>
				";
			} else {
				$content = "
					<h1>Commande acceptée</h1>
					<div>Félicitations votre commande à été enregistrée avec succès, <br />
					un mail contenant les bons de commande de vos séances vous a été envoyé à l'adresse <em>$UserEmail</em></div>
				";
			}
		break;

		case "paymentcancel":
			$css		= "style_cmd.css";
			$cadeau 	= "misso_page3_cadeau.png";
			$marge = "<br />";
			$donnez = "donnez-vous4.gif";
			$menu = $langue == 'EN' ? "EN_misso_page4_menu.gif" : "misso_page4_menu.gif";

			if ($langue == 'EN')
			{
				$content = "
					<h1>Ordercanceled</h1>
					<
				";
			} else {
				$content = "
					<h1>Commande annulée</h1>

				";
			}
		break;
	}

?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<title>Misso - Massages</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" href="common.css" rel="stylesheet">
	<link type="text/css" href="<?php echo $css; ?>" rel="stylesheet">
	<script language="javascript" type="text/javascript" src="lib/js/misso_home.js"></script>
	<link rel="stylesheet" type="text/css" href="lib/mg/css/mg.css" />
	<style type="text/css">
		#ContentText {
			position:relative;
			overflow:hidden;
			padding:20px;
		}
		#cmpScroll {
			float:right;
			height:100%;
			width:21px;
		}
	</style>
	<script type="text/javascript" src="lib/mg/js/mg.js"></script>
	<script type="text/javascript">
		MG.setImgPath("lib/mg/imgs/");
		Misso.lang = "<? echo $langue?>"
	</script>
</head>
<body onload="Misso.init();">
<div id="main">
	<div id="logo_area"></div>


<div id="language_switch">
<?php

	$retour =  $_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'];
	$retour= substr($retour,1);
	if ($langue=='EN') {
		print "<a style=\"text-decoration:none;color:silver;\" href=\"langue.php?retour=" . $retour ."\">FR</a>&nbsp;&nbsp;.&nbsp;&nbsp;<span class=\"active\">EN</span>";
	}
	else
	{
		print "<span class=\"active\">FR</span>&nbsp;&nbsp;.&nbsp;&nbsp;<a style=\"text-decoration:none;color:silver;\" href=\"langue.php?retour=" . $retour ."\">EN</a>";
	}

?>

</div>




<div id="panier" onclick="window.location='misso_massage.php?page=panier';">
<img src="images/panier.png" style="float:left">
<?php

	$nb = 0;
	$pluriel = "";
	if (isset($_SESSION["cart"]))
	{
		foreach ($_SESSION["cart"] AS $v)
			$nb += $v["qty"];
		if ($nb > 1)
			$pluriel = "s";
	}

?>

<?php echo VOTREPANIER; ?><br /><?php print $nb; ?> article<?php print $pluriel; ?></div>
<div id="adresse"><img src="images/ground/misso_adresse.png"></div>
<div id="appelez"><img src="images/ground/<? echo $langue == 'EN' ? "EN_misso_appelez.png" : "misso_appelez.png"; ?>"></div>
<div id="menu"><img src="images/ground/<?php echo $menu; ?>" usemap="#menuhome" border="0"></div>

<div id="windowground">
	<?php echo $titre; ?>
</div>
<div id="window">

	<div id="cmpCtn">
		<div id="ContentText">
			<?php
				echo $marge;
				echo $content;
			?>
		</div>
	</div>
	<div id="cmpScroll"></div>

</div>

<div id="misso_home_footer_adresse"><img src="images/ground/misso_home_footer_adresse.png"></div>
<div id="misso_home_footer_google"><img src="images/ground/misso_home_footer_google.png"></div>
<div id="misso_home_footer_facebook">
	<a href="http://www.facebook.com/pages/Massage-Misso-Thaistyle/179026482109539">
		<img src="images/ground/misso_home_footer_facebook.png" alt="Facebook MISSO">
	</a>
</div>

<?php
	if ($langue=='EN') { $cat = 21; } else { $cat = 18; }
	$sql ="SELECT introtext FROM jos_content WHERE catid=$cat AND state=1 ORDER BY ordering";
	$rs = mysql_query($sql);
	$CGV = "";
	while ($rw = mysql_fetch_array($rs))
		$CGV .= $rw["introtext"];
	$CGV = str_replace("'","\'", $CGV);
	$CGV = str_replace('"',"\'", $CGV);
	$CGV = str_replace(chr(10),"", $CGV);
	$CGV = str_replace(chr(13),"", $CGV);

	$CGV_title = $langue == 'EN' ? "General terms of sale" : "Conditions générales de vente";
?>
<div style="bottom: -17px;color: #888888;cursor: pointer;font-family: arial;left: 137px;position:absolute;font-size:12px" onclick="Misso.popText('<? echo $CGV ?>', {width:'600px'});"><? echo $CGV_title ?></div>

<div id="misso_home_cadeau"><img src="images/ground/<?php echo $cadeau; ?>" usemap="#cadeau" border="0"></div>
<div id="misso_voir_panier"><a href="misso_massage.php?page=panier"><img src="images/ground/voirpanier.png" border="0" /></a></div>










<!--<div id="misso_paiement">
	<div class="title">Paiement sécurisé par carte</div>
	<?php
		/*$sql ="SELECT * FROM jos_content WHERE catid=17  ORDER BY id DESC LIMIT 17";
		$rs = mysql_query($sql);
		if ($rw = mysql_fetch_array($rs))
		{
			do {
				print "<div class='texte'>" . $rw["introtext"] . "</div>";
			} while($rw = mysql_fetch_array($rs));
		}*/
	?>
</div>-->






















<div id="misso_home_testimonial">
	<div class="title">
		<?php echo $langue == "EN" ? "What our customers say" : "Ce que disent nos clients"; ?>
	</div>

	<div id="donnerAvis__cmp">
		<div id="donnerAvis__ctn">
			<div id="donnerAvis__TEXT">
				<?php include "module__donneravis.inc"; ?>
			</div>
		</div>
		<div id="donnerAvis__scroll"></div>
	</div>
	<?php include "module__contact.inc"; ?>


	<div id="btnAvis" style="border:none;cursor:pointer;">
		<?php echo $langue == "EN" ? "+ your opinion" : "+ donnez-nous votre avis" ?>
	</div>

<script type="text/javascript">
	MG.Events.on(MG.$("btnAvis"), "click", function (e) {
		Misso.popText('<? echo addslashes(trim($HTML_DONNER_AVIS)) ?>', {height:"400px", noScrollBar:true});
	});

	MG.Events.on(MG.$("misso_home_footer_google"), "click", function (e) {
		Misso.popText('<? echo addslashes('<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.ch/maps?f=q&amp;source=s_q&amp;hl=fr&amp;geocode=&amp;q=maupas+27+lausanne&amp;sll=46.362093,9.036255&amp;sspn=3.654416,10.799561&amp;ie=UTF8&amp;hq=&amp;hnear=Rue+du+Maupas+27,+1004+Lausanne,+Vaud&amp;ll=46.524855,6.62501&amp;spn=0.020669,0.036478&amp;z=14&amp;output=embed"></iframe><br /><small><a href="http://maps.google.ch/maps?f=q&amp;source=embed&amp;hl=fr&amp;geocode=&amp;q=maupas+27+lausanne&amp;sll=46.362093,9.036255&amp;sspn=3.654416,10.799561&amp;ie=UTF8&amp;hq=&amp;hnear=Rue+du+Maupas+27,+1004+Lausanne,+Vaud&amp;ll=46.524855,6.62501&amp;spn=0.020669,0.036478&amp;z=14" style="color:#0000FF;text-align:left">Agrandir le plan</a></small>') ?>', {height:"440px", noScrollBar:true});
	});
</script>

</div>
</div>
<map name="map1">
<area shape="circle" coords="640,339,20" href="javascript:next_image();"  alt="Suivante" />
<area shape="circle" coords="691,339,20" href="javascript:prev_image();" alt="Pr&eacute;c&eacute;dente" />
</map>
<map name="cadeau">
<area shape="rect" coords="15,56,183,86" href="misso_massage.php?page=4"  alt="Cadeaux" />
</map>

<map name="menuhome">
	<area shape="rect" coords="0,0,126,35" href="index.php"  alt="Suivante" />
	<area alt="Suivante" href="misso_massage.php?page=1" coords="0,35,126,70" shape="rect" />
	<area alt="Suivante" href="misso_massage.php?page=2" coords="0,71,126,102" shape="rect" />
	<area alt="Suivante" href="misso_massage.php?page=3" coords="0,102,126,140" shape="rect" />
	<area alt="Suivante" href="misso_massage.php?page=4" coords="0,140,126,175" shape="rect" />
	<area alt="Suivante" href="misso_massage.php?page=5" coords="0,175,126,210" shape="rect" />
	<area alt="Suivante" href="misso_massage.php?page=6" coords="0,210,126,245" shape="rect" />
	<area alt="Suivante" coords="0,246,126,290" shape="rect" id="btnContact" style="cursor:pointer" />
</map>
<script type="text/javascript">
	MG.Events.on(MG.$("btnContact"), "click", function (e) {
		Misso.popText('<? echo addslashes(trim($HTML_CONTACT)) ?>', {height:"500px", noScrollBar:true});
	});
</script>

</body>
</html>