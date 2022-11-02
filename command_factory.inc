<?php

class CommandFacoty
{
	protected $DBmysqli;

	public $id = null;
	public $Ref = "";
	public $Price = "";
	public $paymentType = "";

	public $Lang = "";
	public $Name = "";
	public $Prenom = "";
	public $Email = "";
	public $Zip = "";
	public $Addr = "";
	public $Pays = "";
	public $Town = "";
	public $Tel = "";



	public function __construct()
	{
		//require_once "lib/php/mysqli.inc";
		require_once "lib/php/paramcheck.inc";
		//$this->DBmysqli = DBmysqli::getInstance();
	}


	public function registerBeforePayment( $coordonnees )
	{
		$r = true;

		$sessionId = session_id();
        $userid= $_SESSION['user'];
        $message = $_SESSION['user'] . "-";
        $message = session_id() . "-";


        DB::query("SELECT * FROM commandes WHERE session=%s ", $sessionId );
        $nombre = DB::count();
       // $prix =  $nombre * 150;

        $prix =  $coordonnees['total'];

      //  $user = DB::queryFirstRow("SELECT * FROM H30cbv_users WHERE ID=%s", $nom );



        $name           = $coordonnees['nom'];
        $email          = $coordonnees['email'];
        $prenom         = $coordonnees['prenom'];
        $cp             = $coordonnees['cp'];
        $localite       = $coordonnees['localite'];
        $telephone      = $coordonnees['telephone'];
        $adresse        = $coordonnees['adresse'];

        $reference = date("Ymdis-U");

        $texte = $name . "\n";
        $texte .= $email . "\n";
        $texte .= $prenom . "\n";
        $texte .= $cp . "\n";
        $texte .= $localite . "\n";
        $texte .= $telephone . "\n";
        $texte .= $adresse . "\n";
        $texte .= $sessionId . "\n";
        $texte .= $userid . "\n";
        $texte .= $sessionId . "\n";
        $texte .= $sessionId . "\n";

       DB::update('commandes', array(
        'payReference' => "$reference",
        'nom' => "$name",
        'prenom' => "$prenom",
        'email' => "$email",
        'adresse' => "$adresse",
        'cp' => "$cp",
        'localite' => "$localite",
        'telephone' => "$telephone"
         ), "session=%s", session_id());

        mail("info@cyberiade.ch","Préparation de " . $reference . " : " . session_id() , $texte);

		if ( 1==1 )
		{
			$this->Prenom = "$prenom";
			$this->paymentType ="postfinance";
			$this->Lang = "FR";
			$this->Name = "$name";
			$this->Email = "$email";
			$this->Zip = "$cp";
			$this->Addr = "$adresse";
			$this->Town = "$localite";
			$this->Pays = "CH";
			$this->Tel = "$telephone";
			$this->Ref = $reference;
			$this->Price = $prix;
			$r = true;
			}
		return $r;
	}

	/**
	 * met à jours le shaIn de la commande courrament initaliser
	 */
	public function updateSHAin($sha)
	{
		if (ParamCheck::is_id($this->id))
		{
			$sql = "
				UPDATE
					commande
				SET
					SHAin = ".$this->DBmysqli->quote($sha)."
				WHERE
					id = {$this->id}
			";
			$this->DBmysqli->exec($sql);
		}
	}

	/**
	 * Finit le processus de commande après qu'elle soit payer
	 * @param array paramètre relatif à la commande
	 */
	public function finaliseAfterPaid(array $param)
	{
		require_once "lib/php/php_to_pdf/phpToPDF.php";
		$r = false;
		$this->DBmysqli->begin();
		$sql = "
			UPDATE
				commande
			SET
				`state` = 'paid',
				`PAYID` = ".$this->DBmysqli->quote($param["PAYID"]).",
				`CARDNO` = ".$this->DBmysqli->quote($param["CARDNO"])."
			WHERE
				`ref` = ".$this->DBmysqli->quote($param["orderID"])."
		";
		if ($this->DBmysqli->exec($sql) == 1)
		{
			$sql = "
				SELECT
					CA.label,
					CA.ref AS refProduct,
					CA.qty,
					CA.BC,
					C.email,
					C.clientname,
					C.clientprenom,
					C.ref,
					C.state,
					C.date,
					C.price,
					C.lang,
					C.addr,
					C.zip,
					C.pays,
					C.town,
					C.tel,
					C.IP,
					C.paymentType
				FROM
					commandeArticle AS CA
					JOIN commande AS C ON C.id = CA.ext__commande__id
				WHERE
					C.ref = ".$this->DBmysqli->quote($param["orderID"])."
			";
			$Email = "";
			$labels = $this->DBmysqli->fetchAll($sql, PDO::FETCH_ASSOC);
			if (!empty($labels))
			{
				$lang_label = array("fr_FR" => "Français", "en_US" => "Anglais", "it_IT" => "Italien", "de_DE" => "Allemand");

				$LastName = $labels[0]['clientname'];
				$FirstName = $labels[0]['clientprenom'];
				$Email = $labels[0]['email'];
				$lang = isset($lang_label[$labels[0]['lang']]) ? $lang_label[$labels[0]['lang']] : $labels[0]['lang'];
				$zip = $labels[0]['zip'];
				$addr = $labels[0]['addr'];
				$pays = $labels[0]['pays'];
				$town = $labels[0]['town'];
				$tel = $labels[0]['tel'];

				$paymentType = $labels[0]['paymentType'];
				$orderID = $labels[0]['ref'];
				$price = $labels[0]['price'];
				$IP = $labels[0]['IP'];

				$tableuxMail = "<table border='1px'><tr><td>No commande</td><td>Libellé</td></tr>";
				$files = array();
				for ($i = 0, $ilen = count($labels); $i < $ilen; $i++)
				{
					$tableuxMail .= "<tr><td>{$labels[$i]['BC']}</td><td>{$labels[$i]["label"]}</td></tr>";
					$completeFile = realpath("./boncommande")."/{$labels[$i]['BC']}.pdf";
					$files[] = $completeFile;
					$PTP = new phpToPDF();
					$PTP->AddPage();

					$PTP->Image("./images/bg_voucher.png", 40, 20, 130, 320);
					$PTP->SetFont("helvetica", "", 16);
					$PTP->Text(120, 155, iconv("UTF-8", "ISO-8859-1", "massages traditionnels thaïs"));
					$PTP->Text(120, 168, iconv("UTF-8", "ISO-8859-1", "Vous pouvez réserver"));
					$PTP->Text(120, 174, iconv("UTF-8", "ISO-8859-1", "dès à présent votre"));
					$PTP->Text(120, 180, iconv("UTF-8", "ISO-8859-1", "rendez-vous chez miss o"));
					$PTP->Text(120, 193, iconv("UTF-8", "ISO-8859-1", "Veuillez présenter cette"));
					$PTP->Text(120, 199, iconv("UTF-8", "ISO-8859-1", "confirmation lors"));
					$PTP->Text(120, 205, iconv("UTF-8", "ISO-8859-1", "de votre venue"));

					$PTP->SetFillColor(226, 226, 228);
					$PTP->Rect(25, 216, 56, 14, "F");
					$PTP->Rect(84, 216, 105, 14, "F");
					$PTP->Rect(25, 233, 56, 14, "F");
					$PTP->Rect(84, 233, 105, 14, "F");
					$PTP->Rect(25, 250, 56, 14, "F");
					$PTP->Rect(84, 250, 105, 14, "F");

					$PTP->Text(89, 224.5, iconv("UTF-8", "ISO-8859-1", $labels[$i]['BC']));
					$PTP->SetFont("helvetica", "", 14);
					$PTP->Text(30, 224.5, iconv("UTF-8", "ISO-8859-1", "N° de bon"));
					$PTP->Text(30, 241.5, iconv("UTF-8", "ISO-8859-1", "Traitement"));
					$PTP->Text(30, 258.5, iconv("UTF-8", "ISO-8859-1", "Validité"));
					$PTP->Text(89, 241.5, iconv("UTF-8", "ISO-8859-1", $labels[$i]["label"]));
					$nb_personnes = $labels[$i]["refProduct"] == "25" ? "2" : "1";
					$PTP->Text(89, 258.5, iconv("UTF-8", "ISO-8859-1", "Valable pour $nb_personnes personne - 1 année"));


					$PTP->SetFont("helvetica", "B", 25);
					$PTP->Text(58, 275, iconv("UTF-8", "ISO-8859-1", "www.massagemisso.ch"));
					$PTP->SetFont("helvetica", "", 14);
					$PTP->Text(10, 284, iconv("UTF-8", "ISO-8859-1", "Rendez vous, réservation: thaistyle@massagemisso.ch"));
					$PTP->Text(10, 290, iconv("UTF-8", "ISO-8859-1", "Studio miss o sàrl - rue du maupas 27 - 1004 lausanne +41 21 641 27 81"));

					$PTP->Output($completeFile, "F");
				}
				$tableuxMail .= "</table>";

				require_once "lib/php/mail.inc";
				$Em = new email;
			 	$Em->mail_item(array("reply" => "thaistyle@massagemisso.ch"), array("addr" => $Email, "objet" => "Misso - Bon commande", "msg" => "Bonjour, <br /><br />Veuillez trouver en pièce jointe le ou les bons de commande pour les massages que vous avez commandés.<br /><br />"), $files);


				$MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $LastName<br />Prénom : $FirstName<br />Email : $Email<br />Langue : $lang<br />Code postal : $zip<br />Adresse : $addr<br />Pays : $pays<br />Ville : $town<br />Téléphone : $tel<br /><br />a payé : <br /><br />Type de paiement : $paymentType<br />OrderID : $orderID<br />Prix : $price CHF<br />IP : $IP<br /><br />pour les produits suivants : <br /><br />$tableuxMail<br />";
		 		$Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "thaistyle@massagemisso.ch", "objet" => "Commande de $LastName $FirstName", "msg" => $MSG), $files);


				$MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $LastName<br />Prénom : $FirstName<br />Email : $Email<br />Langue : $lang<br />Code postal : $zip<br />Adresse : $addr<br />Pays : $pays<br />Ville : $town<br />Téléphone : $tel<br /><br />a payé : <br /><br />Type de paiement : $paymentType<br />OrderID : $orderID<br />Prix : $price CHF<br />IP : $IP<br /><br />pour les produits suivants : <br /><br />$tableuxMail<br />";
				$Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "info@cyberiade.ch", "objet" => "Commande de $LastName $FirstName", "msg" => $MSG), $files);

				$r = true;
			}
		}
		if ($r === true)
			$this->DBmysqli->commit();
		else
			$this->DBmysqli->rollBack();
		return $r;
	}


	public function finaliseAfterPaid2(array $param)
	{
	    require_once "lib/php/php_to_pdf/phpToPDF.php";
	    $r = false;


	    $this->DBmysqli->begin();


	    $sql = "
			UPDATE
				commande
			SET
				`state` = 'paid',
				`PAYID` = ".$this->DBmysqli->quote($param["PAYID"]).",
				`CARDNO` = ".$this->DBmysqli->quote($param["CARDNO"])."
			WHERE
				`ref` = ".$this->DBmysqli->quote($param["orderID"])."
		";
	    if ($this->DBmysqli->exec($sql) == 1)

	    {
	        $sql = "
				SELECT
					CA.label,
					CA.ref AS refProduct,
					CA.qty,
					CA.BC,
					C.email,
					C.clientname,
					C.clientprenom,
					C.ref,
					C.state,
					C.date,
					C.price,
					C.lang,
					C.addr,
					C.zip,
					C.pays,
					C.town,
					C.tel,
					C.IP,
					C.paymentType
				FROM
					commandeArticle AS CA
					JOIN commande AS C ON C.id = CA.ext__commande__id
				WHERE
					C.ref = ".$this->DBmysqli->quote($param["orderID"])."
			";


	        $Email = "";
	        $labels = $this->DBmysqli->fetchAll($sql, PDO::FETCH_ASSOC);

	        if (!empty($labels))
	        {

	            $lang_label = array("fr_FR" => "Français", "en_US" => "Anglais", "it_IT" => "Italien", "de_DE" => "Allemand");

	            $LastName = $labels[0]['clientname'];
	            $FirstName = $labels[0]['clientprenom'];
	            $Email = $labels[0]['email'];
	            $lang = isset($lang_label[$labels[0]['lang']]) ? $lang_label[$labels[0]['lang']] : $labels[0]['lang'];
	            $zip = $labels[0]['zip'];
	            $addr = $labels[0]['addr'];
	            $pays = $labels[0]['pays'];
	            $town = $labels[0]['town'];
	            $tel = $labels[0]['tel'];

	            $lang = utf8_encode($lang);
	            $paymentType = $labels[0]['paymentType'];
	            $orderID = $labels[0]['ref'];
	            $price = $labels[0]['price'];
	            $IP = $labels[0]['IP'];

	            $tableuxMail = "<table border='1px'><tr><td>No commande</td><td>Libell&eacute;</td></tr>";
	            $files = array();

	            for ($i = 0, $ilen = count($labels); $i < $ilen; $i++)
	            {
	            $tableuxMail .= "<tr><td>{$labels[$i]['BC']}</td><td>{$labels[$i]["label"]}</td></tr>";
	            $completeFile = realpath("./boncommande2")."/{$labels[$i]['BC']}.pdf";
	            $files[] = $completeFile;
	            $PTP = new phpToPDF();
	            $PTP->AddPage();

	            $PTP->Image("./images/voucher.jpg", 0, 0, 210, 297);
	            //	$PTP->SetFont("helvetica", "", 16);
	            //	$PTP->Text(120, 155,  "massages traditionnels thaïs");
	            //	$PTP->Text(120, 168,  "Vous pouvez réserver");
	            //		$PTP->Text(120, 174, "dès à présent votre");
	            //		$PTP->Text(120, 180, "rendez-vous chez miss o");
	            //		$PTP->Text(120, 193, "Veuillez présenter cette");
	            //		$PTP->Text(120, 199, "confirmation lors");
	            //		$PTP->Text(120, 205, "de votre venue");

	            //		$PTP->SetFillColor(226, 226, 228);
	            //		$PTP->Rect(25, 216, 56, 14, "F");
	            //		$PTP->Rect(84, 216, 105, 14, "F");
	            //		$PTP->Rect(25, 233, 56, 14, "F");
	            //		$PTP->Rect(84, 233, 105, 14, "F");
	                //		$PTP->Rect(25, 250, 56, 14, "F");
	                //		$PTP->Rect(84, 250, 105, 14, "F");
	                $PTP->SetTextColor(90, 90, 90);
	                $PTP->SetFont("helvetica", "", 14);
	                $PTP->Text(135, 34, $labels[$i]['BC']);
	                $PTP->Text(135, 51, date("d.m.Y"));
	                //		$PTP->Text(30, 224.5, "N° de bon");
//					$PTP->Text(30, 241.5, "Traitement");
	//					$PTP->Text(30, 258.5, "Validité");
	$PTP->SetFont("helvetica", "", 16);
	                $PTP->Text(15, 241.5, $labels[$i]["label"]);
	                $nb_personnes = $labels[$i]["refProduct"] == "25" ? "2" : "1";
	                $plu_personnes = $labels[$i]["refProduct"] == "25" ? "s" : "";
	                $PTP->SetFont("helvetica", "", 13);
	                $PTP->Text(100, 65, "Valable pour $nb_personnes personne$plu_personnes - 1 an");


	                //					$PTP->SetFont("helvetica", "B", 25);
	                //					$PTP->Text(58, 275, "www.massagemisso.ch");
	                //					$PTP->SetFont("helvetica", "", 14);
	                //					$PTP->Text(10, 284, "Rendez vous, réservation: thaistyle@massagemisso.ch");
	                //					$PTP->Text(10, 290, "Studio miss o sàrl - rue du maupas 27 - 1004 lausanne +41 21 641 27 81");

					$PTP->Output($completeFile, "F");
	//					echo "http://www.massagemisso.ch/boncommande/" . $labels[$i]['BC'] . ".pdf";
				}
				$tableuxMail .= "</table>";

	                require_once "lib/php/mail.inc";
	                $Em = new email;
	                $Em->mail_item(array("reply" => "thaistyle@massagemisso.ch"), array("addr" => $Email, "objet" => "Misso - Bon commande", "msg" => "Bonjour, <br /><br />Veuillez trouver en pi&egrave;ce jointe le ou les bons de commande pour les massages que vous avez command&eacute;s.<br /><br />"), $files);


	                $MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $LastName<br />Pr&eacute;nom : $FirstName<br />Email : $Email<br />Langue : $lang<br />Code postal : $zip<br />Adresse : $addr<br />Pays : $pays<br />Ville : $town<br />T&eacute;l&eacute;phone : $tel<br /><br />a pay&eacute; : <br /><br />Type de paiement : $paymentType<br />OrderID : $orderID<br />Prix : $price CHF<br />IP : $IP<br /><br />pour les produits suivants : <br /><br />$tableuxMail<br />";
	                $Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "thaistyle@massagemisso.ch", "objet" => "Commande de $LastName $FirstName", "msg" => $MSG), $files);


					$MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $LastName<br />Pr&eacute;nom : $FirstName<br />Email : $Email<br />Langue : $lang<br />Code postal : $zip<br />Adresse : $addr<br />Pays : $pays<br />Ville : $town<br />T&eacute;l&eacute;phone : $tel<br /><br />a pay&eacute; : <br /><br />Type de paiement : $paymentType<br />OrderID : $orderID<br />Prix : $price CHF<br />IP : $IP<br /><br />pour les produits suivants : <br /><br />$tableuxMail<br />";
					$Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "eey@itperformance.ch", "objet" => "Commande de $LastName $FirstName", "msg" => $MSG), $files);


					$r = true;
				}
			}
			if ($r === true)
				$this->DBmysqli->commit();
			else
				$this->DBmysqli->rollBack();
			return $r;
		}







		public function finaliseAfterPaid3(array $param)
		{
		    require_once "lib/php/php_to_pdf/phpToPDF.php";
		    $r = false;


		    $this->DBmysqli->begin();


		    $sql = "
			UPDATE
				commande
			SET
				`state` = 'paid',
				`PAYID` = ".$this->DBmysqli->quote($param["PAYID"]).",
				`CARDNO` = ".$this->DBmysqli->quote($param["CARDNO"])."
			WHERE
				`ref` = ".$this->DBmysqli->quote($param["orderID"])."
		";
		    if ($this->DBmysqli->exec($sql) == 1)

		    {
		        $sql = "
				SELECT
					CA.label,
					CA.ref AS refProduct,
					CA.qty,
					CA.BC,
					C.email,
					C.clientname,
					C.clientprenom,
					C.ref,
					C.state,
					C.date,
					C.price,
					C.lang,
					C.addr,
					C.zip,
					C.pays,
					C.town,
					C.tel,
					C.IP,
					C.paymentType
				FROM
					commandeArticle AS CA
					JOIN commande AS C ON C.id = CA.ext__commande__id
				WHERE
					C.ref = ".$this->DBmysqli->quote($param["orderID"])."
			";


		        $Email = "";
		        $labels = $this->DBmysqli->fetchAll($sql, PDO::FETCH_ASSOC);

		        if (!empty($labels))
		        {

		            $lang_label = array("fr_FR" => "Français", "en_US" => "Anglais", "it_IT" => "Italien", "de_DE" => "Allemand");

		            $LastName = $labels[0]['clientname'];
		            $FirstName = $labels[0]['clientprenom'];
		            $Email = $labels[0]['email'];
		            $lang = isset($lang_label[$labels[0]['lang']]) ? $lang_label[$labels[0]['lang']] : $labels[0]['lang'];
		            $zip = $labels[0]['zip'];
		            $addr = $labels[0]['addr'];
		            $pays = $labels[0]['pays'];
		            $town = $labels[0]['town'];
		            $tel = $labels[0]['tel'];

		            $lang = utf8_encode($lang);
		            $paymentType = $labels[0]['paymentType'];
		            $orderID = $labels[0]['ref'];
		            $price = $labels[0]['price'];
		            $IP = $labels[0]['IP'];

		            $tableuxMail = "<table border='1px'><tr><td>No commande</td><td>Libell&eacute;</td></tr>";
		            $files = array();

		            for ($i = 0, $ilen = count($labels); $i < $ilen; $i++)
		            {
		            $tableuxMail .= "<tr><td>{$labels[$i]['BC']}</td><td>{$labels[$i]["label"]}</td></tr>";
		            $completeFile = realpath("./boncommande2")."/{$labels[$i]['BC']}.pdf";
		            $files[] = $completeFile;
		            $PTP = new phpToPDF();
		            $PTP->AddPage();

		            $PTP->Image("./images/voucher.jpg", 0, 0, 210, 297);
		            //	$PTP->SetFont("helvetica", "", 16);
		            //	$PTP->Text(120, 155,  "massages traditionnels thaïs");
		            //	$PTP->Text(120, 168,  "Vous pouvez réserver");
		            //		$PTP->Text(120, 174, "dès à présent votre");
		            //		$PTP->Text(120, 180, "rendez-vous chez miss o");
		            //		$PTP->Text(120, 193, "Veuillez présenter cette");
		            //		$PTP->Text(120, 199, "confirmation lors");
		            //		$PTP->Text(120, 205, "de votre venue");

		            //		$PTP->SetFillColor(226, 226, 228);
		            //		$PTP->Rect(25, 216, 56, 14, "F");
		            //		$PTP->Rect(84, 216, 105, 14, "F");
		            //		$PTP->Rect(25, 233, 56, 14, "F");
		            //		$PTP->Rect(84, 233, 105, 14, "F");
		                //		$PTP->Rect(25, 250, 56, 14, "F");
		                //		$PTP->Rect(84, 250, 105, 14, "F");
		                $PTP->SetTextColor(90, 90, 90);
		                $PTP->SetFont("helvetica", "", 14);
		                $PTP->Text(135, 34, $labels[$i]['BC']);
		                $PTP->Text(135, 51, date("d.m.Y"));
		                //		$PTP->Text(30, 224.5, "N° de bon");
//					$PTP->Text(30, 241.5, "Traitement");
		//					$PTP->Text(30, 258.5, "Validité");
		$PTP->SetFont("helvetica", "", 16);
		                $PTP->Text(15, 241.5, $labels[$i]["label"]);
		                $nb_personnes = $labels[$i]["refProduct"] == "25" ? "2" : "1";
		                $plu_personnes = $labels[$i]["refProduct"] == "25" ? "s" : "";
		                $PTP->SetFont("helvetica", "", 13);
		                $PTP->Text(100, 65, "Valable pour $nb_personnes personne$plu_personnes - 1 an");


		                //					$PTP->SetFont("helvetica", "B", 25);
		                //					$PTP->Text(58, 275, "www.massagemisso.ch");
		                //					$PTP->SetFont("helvetica", "", 14);
		                //					$PTP->Text(10, 284, "Rendez vous, réservation: thaistyle@massagemisso.ch");
		                //					$PTP->Text(10, 290, "Studio miss o sàrl - rue du maupas 27 - 1004 lausanne +41 21 641 27 81");

					$PTP->Output($completeFile, "F");
	//					echo "http://www.massagemisso.ch/boncommande/" . $labels[$i]['BC'] . ".pdf";
				}
				$tableuxMail .= "</table>";

		                require_once "lib/php/mail.inc";
		                $Em = new email;
		                $Em->mail_item(array("reply" => "thaistyle@massagemisso.ch"), array("addr" => $Email, "objet" => "Misso - Bon commande", "msg" => "Bonjour, <br /><br />Veuillez trouver en pi&egrave;ce jointe le ou les bons de commande pour les massages que vous avez command&eacute;s.<br /><br />"), $files);


		                $MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $LastName<br />Pr&eacute;nom : $FirstName<br />Email : $Email<br />Langue : $lang<br />Code postal : $zip<br />Adresse : $addr<br />Pays : $pays<br />Ville : $town<br />T&eacute;l&eacute;phone : $tel<br /><br />a pay&eacute; : <br /><br />Type de paiement : $paymentType<br />OrderID : $orderID<br />Prix : $price CHF<br />IP : $IP<br /><br />pour les produits suivants : <br /><br />$tableuxMail<br />";
		                $Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "thaistyle@massagemisso.ch", "objet" => "Commande de $LastName $FirstName", "msg" => $MSG), $files);


						$MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $LastName<br />Pr&eacute;nom : $FirstName<br />Email : $Email<br />Langue : $lang<br />Code postal : $zip<br />Adresse : $addr<br />Pays : $pays<br />Ville : $town<br />T&eacute;l&eacute;phone : $tel<br /><br />a pay&eacute; : <br /><br />Type de paiement : $paymentType<br />OrderID : $orderID<br />Prix : $price CHF<br />IP : $IP<br /><br />pour les produits suivants : <br /><br />$tableuxMail<br />";
						$Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "eey@itperformance.ch", "objet" => "Commande de $LastName $FirstName", "msg" => $MSG), $files);


						$r = true;
					}
				}
				if ($r === true)
					$this->DBmysqli->commit();
				else
					$this->DBmysqli->rollBack();
				return $r;
			}











	/**
	 * Récupère les donnée d'une commande par son identifiant orderID
	 */
	public function get($refOID)
	{
		$sql = "
			SELECT
				`id`,
				`ref`,
				`state`,
				`date`,
				`price`,
				`lang`,
				`clientname`,
				`email`,
				`addr`,
				`zip`,
				`pays`,
				`town`,
				`tel`,
				`IP`,
				`paymentType`
			FROM
				commande
			WHERE
				`ref` = ".$this->DBmysqli->quote($refOID)."
		";
		$data = $this->DBmysqli->fetch($sql, PDO::FETCH_ASSOC);
		if (!empty($data))
		{
			$this->id = $data["id"];
			$this->Ref = $data["ref"];
			$this->Price = $data["price"];
			$this->Lang = $data["lang"];
			$this->Name = $data["clientname"];
			$this->Email = $data["email"];
			$this->Zip = $data["zip"];
			$this->Addr = $data["addr"];
			$this->Pays = $data["pays"];
			$this->Town = $data["town"];
			$this->Tel = $data["tel"];
			$this->paymentType = $data["paymentType"];

			$sql = "
				SELECT
					ext__commande__id,
					BC,
					ref,
					label,
					qty,
					prix,
					monnaie
				FROM
					commandeArticle
				WHERE
					ext__commande__id = ".$this->id."
			";
			$data["articles"] = $this->DBmysqli->fetchAll($sql, PDO::FETCH_ASSOC);
		}

		return $data;
	}

	/**
	 * Annule une commande
	 * @param string orderID
	 * @return boolean
	 */
	public function cancelCommande($orderID)
	{
		$sql = "
			UPDATE
				commande
			SET
				`state` = 'abort'
			WHERE
				`ref` = ".$this->DBmysqli->quote($orderID)."
		";
		return $this->DBmysqli->exec($sql) == 1 ? true : false;
	}
}


?>