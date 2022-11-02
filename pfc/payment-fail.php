<?php
    include ("../init.php");

    // Seek cart list for adding sale items

    $sessionId = session_id();
    $result = DB::query("SELECT *, count(*) as nb, commandes.Prix as prixfinal, packs.ID as PID FROM commandes

    INNER JOIN packs ON ( commandes.PAck = packs.ID )

    INNER JOIN prestations ON ( prestations.ID = packs.PrestationID )

    WHERE Session=%s

    GROUP BY Pack

    ", $sessionId);

    if(count($result) == 0)
        header('Location: ' . $baseurl);

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
                    <i class="fas fa-exclamation-circle" style="color: #dd380b; font-size: 60px;"></i>
                    <h2 style="padding-top:20px;">Payment Failed</h2>
                    <h5 style="border-bottom:solid 1px lightgray; padding-bottom:50px;">Please go back to site and try
                        again.
                    </h5>
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
                        <p><?php echo $row['Libelle']. ' ('. $row['Prestation']. ')' ?></p>
                        <p>CHF <?php echo number_format($row['Prix'], 2)?></p>
                    </div>
                    <?php $total += $row['Prix'];} ?>
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
                        <button class="btn btn-danger">Back to Site</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>