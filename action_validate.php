<?php

include ("init.php");


require_once "command_factory.inc";

require_once "postfinance.inc";
require_once (__DIR__. '/pfc-sdk/autoload.php');


header('Content-Type: text/html; charset=utf-8');



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

        // Configuration

        $spaceId = 405;
        $userId = 512;
        $secret = 'FKrO76r5VwJtBrqZawBspljbBNOxp5veKQQkOnZxucQ=';

        // Setup API client
        $client = new \PostFinanceCheckout\Sdk\ApiClient($userId, $secret);

        // Create transaction
        $items = array();

        foreach($result as $row){
            $lineItem = new \PostFinanceCheckout\Sdk\Model\LineItemCreate();
            $lineItem->setName($row['Libelle']. ' ('. $row['Prestation']. ')');
            $lineItem->setUniqueId($row['id']);
            // $lineItem->setSku('red-t-shirt-123');
            $lineItem->setQuantity(1);
            $lineItem->setAmountIncludingTax((int)$row['Prix']);
            $lineItem->setType(\PostFinanceCheckout\Sdk\Model\LineItemType::PRODUCT);

            array_push($items, $lineItem);
        }

        $transactionPayload = new \PostFinanceCheckout\Sdk\Model\TransactionCreate();
        $transactionPayload->setCurrency('CHF');
        $transactionPayload->setLineItems($items);
        $transactionPayload->setAutoConfirmationEnabled(true);

        // $baseurl = sprintf(
        //     "%s://%s",
        //     isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        //     $_SERVER['SERVER_NAME']
        //   );

        $transactionPayload->setSuccessUrl($baseurl. "/pfc/payment-success.php");   
        $transactionPayload->setFailedUrl($baseurl. "/pfc/payment-fail.php");


        $transaction = $client->getTransactionService()->create($spaceId, $transactionPayload);

        // Create Payment Page URL:
        $redirectionUrl = $client->getTransactionPaymentPageService()->paymentPageUrl($spaceId, $transaction->getId());
        
        header('Location: ' . $redirectionUrl);

				// $PostFinance = new PostFinance;

				// $content .= $PostFinance->makeFormAutoSubmit( $CommandFacoty );



                // echo $content;



?>