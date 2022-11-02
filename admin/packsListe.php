<?php
$cours = "active";
session_start();

include '../lib/php/mysql.class.php';
include '../lib/php/config.php';

$id = $_GET['id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=2">
<meta name="description" content="">
<meta name="author" content="">


<title>MISSO les packs</title>


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

	<div class="container">

		<div class="row" style="margin-bottom: 30px">
			<div class="col-xs-12  text-center">
				<button id="sautPlus" type="button" class="btn btn-success"
					aria-label="Left Align"
					onclick="window.location.href='massagesListe.php'">
					<span class="glyphicon glyphicon-home" aria-hidden="true"></span>
					Revenir aux massages
				</button>
			</div>
			</div>

		</div>

	<div class="container">

		<div class="row" style="margin-bottom: 30px">
			<div class="col-xs-12  text-center">
				<button id="sautPlus" type="button" class="btn btn-success"
					aria-label="Left Align"
					onclick="$('#new').css('display','block');">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					Ajouter un pack
				</button>
			</div>
			</div>

		</div>






		<div class="container" id="new" style="display:none;">
	<div class="row">
	<div class="col-xs-12  text-center">
	<h1>Ajouter un pack</h1>
	</div>
	</div>
	<div class="row">

<form method="POST" action="bd_new_pack.php">

<input name="id" type="hidden" class="form-control" id="exampleInputEmail1" value="<?php echo $id; ?>">

<div class="form-group">
<label for="exampleInputEmail1">Article</label>
<input name="article" type="text" class="form-control" id="exampleInputEmail1" placeholder="Libellé du pack">
</div>
<div class="form-group">
<label for="exampleInputEmail1">Prix</label>
<input name="prix" type="text" class="form-control" id="exampleInputEmail1" placeholder="Prix du pack">
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
    echo "<tr><th>Article</th><th>Prix</th><th>Prix spécial</th><th>Action</th></tr>";

$packs = DB::query("SELECT * FROM packs WHERE PrestationID=%i ORDER BY Prix" , $id);
		foreach ($packs as $pack) {


		        echo "<tr><td>" . $pack['Article']

		        . "</td><td>"

                . $pack['Prix']

                . "</td><td>"

                . $pack['PrixSpecial']

                . "</td><td>




    <button id='sautPlus' type='button' class='btn btn-info' aria-label='Left Align' onclick=\"document.location.href='modi_packs.php?massageid=" . $id . "&id=" . $pack['ID'] . "'\">
					<span class='glyphicon glyphicon-edit' ></span>
					Modifier ce pack
				</button>

    <button id='sautPlus' type='button' class='btn btn-danger' aria-label='Left Align' onclick=\"document.location.href='bd_delete_pack.php?id=" . $pack['ID'] . "'\">
					<span class='glyphicon glyphicon-remove' ></span>
					Effacer ce pack
				</button>

             </td></tr>";
		    }
		    ?>
		                </table>

</div>
</div>


</body>
</html>