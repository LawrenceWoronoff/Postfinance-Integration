<?php

require_once "define.inc";


if (isset($_REQUEST["SHASIGN"]))
{
	$PostFinance = new PostFinance;
	$check = $PostFinance->check_SHA_out($_REQUEST, $_REQUEST["SHASIGN"]);
	if ($check === true)
	{
		$CF = new CommandFacoty;
		$CF->cancelCommande($_REQUEST["orderID"]);
	}
}
/*elseif (isset($_REQUEST["DATA"]) && isset($_REQUEST["SIGNATURE"]))
{
	require_once "saferpay.inc";
	$SaferPay = new SaferPay;
	if ($SaferPay->confirm_data_retour())
	{
		$DATA = $SaferPay->extract_retour_data();
		$CF = new CommandFacoty;
		$CF->finaliseAfterPaid($DATA);
	}
}*/

?>

