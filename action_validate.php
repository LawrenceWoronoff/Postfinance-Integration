<?php
include ("init.php");
include ("pfc/pfc-config.php");

require_once "command_factory.inc";

require_once "postfinance.inc";
require_once (__DIR__. '/pfc-sdk/autoload.php');


header('Content-Type: text/html; charset=utf-8');

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function encryptPayId($payId)
{
    $ascii = ord('a');
    $crypted = '';
    $payId = (string) $payId;
    for($i = 0; $i < strlen($payId); $i++) {
        $a = 'a';
        $crypted .= chr($ascii + (int)$payId[$i]);
    }
    return $crypted. "-". generateRandomString();
}


$content = "<h1>Votre demande est analysée avant d'être transférée chez Postfinance. Patientez svp.</h1>";



		$CommandFacoty = new CommandFacoty;



    	$coordonnees['nom']         = $_POST['nom'];

        $coordonnees['email']       = $_POST['email'];

        $coordonnees['prenom']      = $_POST['prenom'];

        $coordonnees['cp']          = $_POST['cp'];

        $coordonnees['localite']    = $_POST['localite'];

        $coordonnees['telephone']   = $_POST['telephone'];

        $coordonnees['adresse']     = $_POST['adresse'];

        $coordonnees['total']       = $_POST['total'];



        $CommandFacoty->registerBeforePayment( $coordonnees);
        


		$content = "<h1>Votre demande est analysée avant d'être transférée chez Postfinance. Patientez svp.</h1>";


        // ========== Post Finance Checkout Integration =========== //
        
        // Seek cart list for adding sale items
        $sessionId = session_id();
        $result = DB::query("SELECT *, count(*) as nb, commandes.Prix as prixfinal, packs.ID as PID FROM commandes

        INNER JOIN packs ON ( commandes.PAck = packs.ID )

        INNER JOIN prestations ON ( prestations.ID = packs.PrestationID )

        WHERE Session=%s

        GROUP BY Pack

        ", $sessionId);


        // Setup API client
        $client = new \PostFinanceCheckout\Sdk\ApiClient($userId, $secret);

        // Create transaction
        $items = array();

        foreach($result as $row){
            $lineItem = new \PostFinanceCheckout\Sdk\Model\LineItemCreate();
            $lineItem->setName($row['Libelle']. ' ('. $row['Prestation']. ')');
            $lineItem->setUniqueId($row['id']);
            // $lineItem->setSku('red-t-shirt-123');
            $lineItem->setQuantity((int)$row['nb']);
            $lineItem->setAmountIncludingTax((int)$row['Prix'] * (int)$row['nb']);
            $lineItem->setType(\PostFinanceCheckout\Sdk\Model\LineItemType::PRODUCT);

            array_push($items, $lineItem);
        }

        $transactionPayload = new \PostFinanceCheckout\Sdk\Model\TransactionCreate();
        $transactionPayload->setCurrency('CHF');
        $transactionPayload->setLineItems($items);
        $transactionPayload->setAutoConfirmationEnabled(true);

        $transaction = $client->getTransactionService()->create($spaceId, $transactionPayload);
        $transactionId = $transaction->getId();

        $transactionPending = new \PostFinanceCheckout\Sdk\Model\TransactionPending();
        $transactionPending->setSuccessUrl($baseurl. "/pfc/payment-success.php?payId=". encryptPayId($transactionId));
        $transactionPending->setFailedUrl($baseurl. "/pfc/payment-fail.php?payId=". encryptPayId($transactionId));
        $transactionPending->setId($transaction->getId());
        $transactionPending->setVersion($transaction->getVersion());
        $client->getTransactionService()->update($spaceId, $transactionPending);

        // Create Payment Page URL:
      
        $redirectionUrl = $client->getTransactionPaymentPageService()->paymentPageUrl($spaceId, $transactionId);
        
        header('Location: ' . $redirectionUrl);

        // $PostFinance = new PostFinance;

        // $content .= $PostFinance->makeFormAutoSubmit( $CommandFacoty );

        // echo $content;
   
?>
