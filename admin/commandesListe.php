<?php
$cours = "active";
session_start();

include '../lib/php/mysql.class.php';
include '../lib/php/config.php';

if (! isset($_SESSION["user"])) {
    //header("location:login.php");
}

if ($_SESSION["level"] != 2) {
    header("location:../index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=2">
<meta name="description" content="">
<meta name="author" content="">


<title>MISSO les commandes</title>


<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">


<style type="text/css">
.color-red {
	color: red;
}
</style>

<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"
	type="text/javascript">
		src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"
		type="text/javascript">
		</script>



</head>


<body>

<?php include "inc_navigation.php"; ?>









		<div class="container" id="new" style="display:none;">
	<div class="row">
	<div class="col-xs-12  text-center">
	<h1>Ajouter un article</h1>
	</div>
	</div>
	<div class="row">

<form method="POST" action="bd_new_article.php">
<div class="form-group">
<label for="exampleInputEmail1">Journal</label>
<input name="libelle" type="text" class="form-control" id="exampleInputEmail1" placeholder="Libell� du massage">
</div>
<div class="form-group">
<button type="submit" class="btn btn-success">Enregistrer</button></div>
</form>
</div>
</div>

















		<div class="container">

	<div class="row">

						<table class="table table-striped">

		   <?php
    echo "<tr>
    <th>Qté</th>
    <th>Heure</th>

    <th>Prestations</th>
    <th>Session (groupe)</th>
    <th>Client</th>

    <th>PF ID</th>
        <th>PDF</th>
    </tr>";

$commandes = DB::query("SELECT *, commandes.Prix as prixfinal FROM commandes
    INNER JOIN packs ON ( packs.ID = commandes.Pack )
    INNER JOIN prestations ON ( prestations.ID = packs.PrestationID )
    ORDER BY Timbre DESC, Session
    LIMIT 300
    ");
		foreach ($commandes as $commande) {


		        echo "<tr>" .
		        "<td>" . $commande['Qte'] . "</td>" .
		        "<td>" . date("d.m H:n", $commande['Timbre'] ) . "</td>" .
		        "<td>" . $commande['Libelle'] . " " . $commande['Article'] . " <b>CHF " . $commande['prixfinal']  . ".-</b></td>" .
		        "<td>" . $commande['Session'] . "</td>" .
		        "<td>" . $commande['nom'] . " " . $commande['prenom'] . "</td>" .
		        "<td>" . $commande['PAYID'] . "</td>" .
		        "<td><a target='_blank' href='../boncommande/" . substr($commande['payReference'],2) . "-" .  $commande['id']. ".pdf'>" . $commande['payReference'] . "</a></td>" .

		        "</td>

        <!--button id='sautPlus' type='button' class='btn btn-primary' aria-label='Left Align' onclick=\"document.location.href='new_photos.php?objetID=" . $massage['ID'] . "'\">
					<span class='glyphicon glyphicon-camera' ></span>
					Photo
				</button>



    <button id='sautPlus' type='button' class='btn btn-info' aria-label='Left Align' onclick=\"document.location.href='modi_massage.php?id=" . $massage['ID'] . "'\">
					<span class='glyphicon glyphicon-edit' ></span>
					Modifier cet article
				</button>

    <button id='sautPlus' type='button' class='btn btn-danger' aria-label='Left Align' onclick=\"document.location.href='bd_delete_massage.php?id=" . $massage['ID'] . "'\">
					<span class='glyphicon glyphicon-remove' ></span>
					Effacer cet article
				</button-->

             </td></tr>";
		    }
		    ?>
		                </table>

</div>
</div>


</body>
</html>