<?php

/**
 * Class servant � construire un formulaire de paiement postfinance
 */
class PostFinance
{
	protected $DBmysqli;




	private $pwdSHAin = "ICP5FMZ#17Q88d!d";
	private $pwdSHAout = "ICP5FMZ#17Q88d!d";

	protected $SHA_IN = array(
	    "PSPID" => "studiomiss",
		"ORDERID" => "",
		"AMOUNT" => "",
		"CURRENCY" => "CHF",
		"LANGUAGE" => "",
		"CN" => "",
		"EMAIL" => "",
		"OWNERADDRESS" => "",
		"OWNERCTY" => "",
		"OWNERTELNO" => "",
		"OWNERTOWN" => "",
		"OWNERZIP" => "",
		"TITLE" => "",
		"BGCOLOR" => "",
		"TXTCOLOR" => "",
		"TBLBGCOLOR" => "",
		"TBLTXTCOLOR" => "",
		"BUTTONBGCOLOR" => "",
		"BUTTONTXTCOLOR" => "",
		"LOGO" => "",
		"FONTTYPE" => ""
	);

	protected $SHA_OUT = array(
		"AAVADDRESS" => "",
		"AAVCHECK" => "",
		"AAVZIP" => "",
		"ACCEPTANCE" => "",
		"ALIAS" => "",
		"AMOUNT" => "",
		"BRAND" => "",
		"CARDNO" => "",
		"CCCTY" => "",
		"CN" => "",
		"COMPLUS" => "",
		"CREATION_STATUS" => "",
		"CURRENCY" => "",
		"CVCCHECK" => "",
		"DCC_COMMPERCENTAGE" => "",
		"DCC_CONVAMOUNT" => "",
		"DCC_CONVCCY" => "",
		"DCC_EXCHRATE" => "",
		"DCC_EXCHRATESOURCE" => "",
		"DCC_EXCHRATETS" => "",
		"DCC_INDICATOR" => "",
		"DCC_MARGINPERCENTAGE" => "",
		"DCC_VALIDHOURS" => "",
		"DIGESTCARDNO" => "",
		"ECI" => "",
		"ED" => "",
		"ENCCARDNO" => "",
		"IP" => "",
		"IPCTY" => "",
		"NBREMAILUSAGE" => "",
		"NBRIPUSAGE" => "",
		"NBRIPUSAGE_ALLTX" => "",
		"NBRUSAGE" => "",
		"NCERROR" => "",
		"ORDERID" => "",
		"PAYID" => "",
		"PM" => "",
		"SCO_CATEGORY" => "",
		"SCORING" => "",
		"STATUS" => "",
		"SUBSCRIPTION_ID" => "",
		"TRXDATE" => "",
		"VC" => ""
	);

	public function __construct()
	{
		//require_once "lib/php/mysqli.inc";
		//require_once "lib/php/paramcheck.inc";
		require_once "command_factory.inc";
		//$this->DBmysqli = DBmysqli::getInstance();
		ksort($this->SHA_OUT);
	}

	/**
	 * Cr�e un formulaire du type postfinance auto envoyer
	 * @param object instance de class CommandFacoty
	 */
	public function makeFormAutoSubmit(CommandFacoty $CF)
	{
		require_once 'character.php';
		$Character = new Character();
				$MASK_ACCENT=array('À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ð','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ');
		$MASK_SANS_ACCENT=array('A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y');



		mail("info@cyberiade.ch","Ordre Postfinance.inc", $CF->Name . " " . $CF->Addr . ":" . $CF->Price*100);


		$CF->Lang =  str_replace($MASK_ACCENT, $MASK_SANS_ACCENT, $Character->clean_string($CF->Lang, "utf-8"));
		$CF->Name = str_replace($MASK_ACCENT, $MASK_SANS_ACCENT, $Character->clean_string($CF->Name, "utf-8"));
		$CF->Addr =  str_replace($MASK_ACCENT, $MASK_SANS_ACCENT, $Character->clean_string($CF->Addr, "utf-8"));
		$CF->Pays =  str_replace($MASK_ACCENT, $MASK_SANS_ACCENT, $Character->clean_string($CF->Pays, "utf-8"));
		$CF->Town =  str_replace($MASK_ACCENT, $MASK_SANS_ACCENT, $Character->clean_string($CF->Town, "utf-8"));
		$CF->Zip = str_replace($MASK_ACCENT, $MASK_SANS_ACCENT, $Character->clean_string($CF->Zip, "utf-8"));

		mail("info@cyberiade.ch","Ordre Postfinance.inc", $CF->Name . " " . $CF->Addr . ":" . $CF->Price*100);

		ksort($this->SHA_IN);
		$this->SHA_IN["ORDERID"] = $CF->Ref;
		$this->SHA_IN["AMOUNT"] = $CF->Price*100;
		$this->SHA_IN["LANGUAGE"] = $CF->Lang;
		$this->SHA_IN["CN"] = $CF->Name;
		$this->SHA_IN["EMAIL"] = $CF->Email;
		$this->SHA_IN["OWNERADDRESS"] = $CF->Addr;
		$this->SHA_IN["OWNERCTY"] = $CF->Pays;
		$this->SHA_IN["OWNERTELNO"] = $CF->Tel;
		$this->SHA_IN["OWNERTOWN"] = $CF->Town;
		$this->SHA_IN["OWNERZIP"] = $CF->Zip;

		$idForm = uniqid();
		$str4hash = "";
		/*https://e-payment.postfinance.ch/ncol/test/orderstandard.asp*////TODO URL TEST
		$str4form = '<form id="'.$idForm.'" action="https://e-payment.postfinance.ch/ncol/prod/orderstandard.asp" method="post">';
		foreach ($this->SHA_IN AS $k => $v)
		{
			if ($v != "")
			{
				$str4hash .= "$k=$v".$this->pwdSHAin;
				$str4form .= '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
			}
		}
		$SHASign = sha1($str4hash);
		$CF->updateSHAin($SHASign);
		//$str4form .= '<input type="hidden" name="SHASIGN" value="'.$SHASign.'" />';
		$str4form .= '<input type="hidden" name="SHASIGN" value="'.$SHASign.'" /><script type="text/javascript">document.getElementById("'.$idForm.'").submit();</script>';

		return $str4form;
	}

	/**
	 * Construit le SHAsing de sortie du au retour de l'api de paiment
	 * et le compare avec celui passer � la fonction
	 * @param array param�tre sha-out
	 * @param string shaSing
	 */
	public function check_SHA_out(array $param, $shaSing)
	{
		foreach ($param AS $k => $v)
		{
			$k = strtoupper($k);
			if (isset($this->SHA_OUT[$k]))
				$this->SHA_OUT[$k] = $v;
		}
		$str4hash = "";
		foreach ($this->SHA_OUT AS $k => $v)
			if (is_string($v) && $v != "")
				$str4hash .= "$k=$v".$this->pwdSHAout;
		$hash = sha1($str4hash);

		mail("info@cyberiade.ch","postfinance.ing", "-->" . strtoupper($hash) . " " . strtoupper($shaSing)  );
		return strtoupper($hash) === strtoupper($shaSing) ? true : false;
	}
}


?>