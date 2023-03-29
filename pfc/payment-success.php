<?php
    include ("../init.php");
    include 'pfc-config.php';
    $sessionId = session_id();

    require_once (__DIR__. '/../pfc-sdk/autoload.php');

    function decryptPayId($crypted)
    {
        $ascii = ord('a');
        $pos = strpos($crypted, "-");
        $crypted = substr($crypted, 0, $pos);
        $payId = '';
        for($i = 0; $i < strlen($crypted); $i++) {
            $payId .= (ord($crypted[$i]) - $ascii);
        }
        return $payId;
    }

    if(!isset($_GET['payId'])){
        echo "This is invalid payment injection";
        return;
    }
    $payId = decryptPayId($_GET['payId']);
    
    // ========= Read Transaction State Variable and Check ======= //

    // Setup API client
    $client = new \PostFinanceCheckout\Sdk\ApiClient($userId, $secret);

    $transactionId = $payId;
    $transaction = $client->getTransactionService()->read($spaceId, $transactionId);
    $status = $transaction->getState();

    // Check if that transaction status is "success"
    if($status != 'FULFILL' && $status != 'COMPLETED'){
        echo "This is not completed postfinace transaction! Warn you to stop hacking our system!";
        return;
    }

    // Check current successed transaction is for current orders. (Checking if PAYID and sessionId matched)
    // If hacker tried to make payment on the already rejected payment transaction ID.
    // But if it is new transaction which don't have payID, it will pass through well..
    $elements = DB::query("SELECT * FROM commandes
                            WHERE PAYID=%s", $payId);
    // var_dump($elements[0]['PAYID']);
    if(count($elements) != 0 && $elements[0]['Session'] != $sessionId){
        echo "Transaction ID is not matched with current session Id! Warn you to stop hacking our system!";
        return;
    }

    // We should add exception if there is no record related with that payId. But later...
    ////////////////////////////////////////////////////////////
    
    // Seek cart list for adding sale items
    
    $result = DB::query("SELECT *, count(*) as nb, commandes.Prix as prixfinal, packs.ID as PID FROM commandes

    INNER JOIN packs ON ( commandes.PAck = packs.ID )

    INNER JOIN prestations ON ( prestations.ID = packs.PrestationID )

    WHERE Session=%s

    GROUP BY Pack

    ", $sessionId);

    if(count($result) == 0){
        echo "There is no items in the bucket!";
        return;
    }
        
    // if(count($result) == 0)
    //     header('Location: ' . $baseurl);

    // Get order details that are paid
    $elements = DB::query("SELECT * FROM commandes
    	INNER JOIN packs ON ( packs.id = commandes.Pack )
	    INNER JOIN prestations ON ( packs.PrestationID = prestations.ID )
		WHERE Session=%s", $sessionId);

    // PDF Generate for each order regarding to the session.    
    // var_dump($elements);

    $files = array();
    require_once "../lib/php/php_to_pdf/phpToPDF.php";
    
    $price = 0;
    $tableuxMail = "";

    foreach($elements as $labels){
        $price += $labels["Prix"] * $labels["Qte"];


        $BC = substr($labels["payReference"] . "-" . $labels["id"],2) ;
	    $tableuxMail.= $BC . " " . $labels["Libelle"] . " " . $labels["Article"] . " ( CHF " . $labels["Prix"] . ")<br />";

        $completeFile = "../boncommande"."/" . $BC  .  ".pdf";
        $files[] = $completeFile;
        // echo $completeFile;
    
        $libelle = str_replace( "'","-", $labels["Libelle"]);
        $libelle = str_replace( " ","-", $labels["Libelle"]);
        $libelle =  utf8_decode($labels["Libelle"]) . " "  .  $labels["Article"];
    
        $PTP = new phpToPDF();
        $PTP->AddPage();
    
        $PTP->Image("../images/Voucher2015F.jpg", 0, 0, 210, 297);
    
        $PTP->SetTextColor(90, 90, 90);
        $PTP->SetFont("helvetica", "", 14);
        $PTP->Text(100, 34, iconv("UTF-8", "ISO-8859-1", "N° bon"));
        $PTP->Text(130, 34, $BC);
        $PTP->Text(100, 51, "Date");
        $PTP->Text(130, 51, date("d.m.Y"));
    
        $PTP->SetFont("helvetica", "", 16);
        $PTP->Text(15, 241.5, $libelle);
    
        $PTP->SetFont("helvetica", "", 13);
        if ($labels["Personnes"]>1) { $plu_personnes="s"; } else { $plu_personnes=''; }
        $PTP->Text(100, 65, "Valable pour " . $labels["Personnes"] . " personne$plu_personnes - 1 an");
    
        $PTP->Output($completeFile, "F");
    }
    require_once "../lib/php/mail.inc";

    if(count($elements) != 0) {
        $email = $elements[0]['email'];
        $nom = $elements[0]['nom'];
        $prenom = $elements[0]['prenom'];
        $cp= $elements[0]["cp"];
        $adresse= $elements[0]["adresse"];
        $localite= $elements[0]["localite"];
        $telephone= $elements[0]["telephone"];

        // ======= Email Sending ======== //

        
        $Em = new email;

        $Em->mail_item(array("reply" => "thaistyle@massagemisso.ch"), array("addr" => $email, "objet" => "Misso - Bon commande", "msg" => "Bonjour, <br /><br />Veuillez trouver en pièce jointe le ou les bons de commande pour les massages que vous avez commandés.<br /><br />"), $files);
    
        $MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $nom<br />Prénom : $prenom<br />Email : $email<br />Code postal : $cp<br />Adresse : $adresse<br />Ville : $localite<br />Téléphone : $telephone<br /><br />a payé CHF $price.-<br /><br />Type de paiement : Postfinance<br />Prix : $price CHF<br />pour les produits suivants : <br /><br />$tableuxMail<br />";
        // $Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "thaistyle@massagemisso.ch", "objet" => "Commande de $prenom $nom", "msg" => $MSG), $files);

        $MSG = "Bonjour, <br /><br />Le client : <br /><br />Nom : $nom<br />Prénom : $prenom<br />Email : $email<br />Code postal : $cp<br />Adresse : $adresse<br />Ville : $localite<br />Téléphone : $telephone<br /><br />a payé CHF $price.-<br /><br />Type de paiement : Postfinance<br />Prix : $price CHF<br />pour les produits suivants : <br /><br />$tableuxMail<br />";
        // $Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "info@cyberiade.ch", "objet" => "xxx $order Commande de $prenom $nom", "msg" => $MSG), $files);
        $Em->mail_item(array("reply" => "no-replay@massagemisso.ch"), array("addr" => "info@cyberiade.ch", "objet" => "Commande de $prenom $nom", "msg" => $MSG), $files);
        
    }

    DB::update('commandes', array(
            'sate' => 'success',
            'PAYID' => $payId
        ), "Session=%s",  $sessionId);   

    session_regenerate_id(true);
?>
<html>

<head>
    <title>Payment Success</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="row h-100">
        <div class="col-6 d-flex justify-content-end">
            <div style="width:600px;padding-top:100px;">
                <center>
                    <i class="fa fa-check-circle" style="color: #4DAA57; font-size: 60px;"></i>
                    <h2 style="padding-top:20px;">Successful Payment</h2>
                    <h5 style="border-bottom:solid 1px lightgray; padding-bottom:50px;">You successfully finished the
                        payment process.</h5>
                </center>
                <div style="padding-top: 20px">
                    <h5>Customer Info</h5>
                    <p class="m-0">Nom : <span><?php echo $result[0]['nom']?></span></p>
                    <p class="m-0">Prénom : <span><?php echo $result[0]['prenom']?></span></p>
                    <p class="m-0">Email : <span><?php echo $result[0]['email']?></span></p>
                    <p class="m-0">Téléphone : <span><?php echo $result[0]['telephone']?></span></p>
                    <p class="m-0">Adresse: <span><?php echo $result[0]['adresse']?></span></p>
                    <p class="m-0">Code postal: <span><?php echo $result[0]['cp']?></span></p>
                    <p class="m-0">Localité: <span><?php echo $result[0]['localite']?></span></p>

                </div>
            </div>
        </div>
        <div class="col-6" style="background-color: #f5f5f5; border-left: 1px solid #d7d7d7">
            <div style="width:600px;padding-top:100px;padding-left: 30px;">
                <div style="border-bottom:solid 1px lightgray; padding:20px 0px;">
                    <?php $total = 0; foreach($result as $row) { ?>
                    <div class="d-flex justify-content-between">
                        <p><?php echo $row['Libelle']. ' ('. $row['Prestation']. ')' ?> <?php echo (int)$row[nb] == 1 ? '' : (' X '. $row[nb]) ?></p>
                        <p>CHF <?php echo number_format((int)$row['Prix'] * (int)$row['nb'], 2)?></p>
                    </div>
                    <?php $total += (int)$row['Prix'] * (int)$row['nb'];} ?>
                </div>
                <div style="border-bottom:solid 1px lightgray; padding:20px 0px;">
                    <div class="d-flex justify-content-between">
                        <p>Subtotal</p>
                        <p>CHF <?php echo number_format($total, 2)?></p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Tax</p>
                        <p>CHF 0.00</p>
                    </div>
                </div>
                <div style="padding:20px 0px;">
                    <div class="d-flex justify-content-between">
                        <p>Total</p>
                        <h3>CHF <?php echo number_format($total, 2)?></h3>
                    </div>
                </div>
                <div class="d-flex justify-content-end" style="margin-top:20px;">
                    <a href="<?php echo $baseurl ?>">
                        <button class="btn btn-primary">Back to Site</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>