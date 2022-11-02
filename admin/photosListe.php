<?php
include "inc_top_admin.php";
$titre = "Photos du cours";
$cours ="active";

$objetID = $_GET['objetID'];

if ($_SESSION["level"]!=2) {    header("location:../index.php");  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=2">
<meta name="description" content="">
<meta name="author" content="">


<title><?php echo $referencement; ?> <?php echo $titre; ?></title>


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
					onclick="document.location.href='new_photos.php?objetID=<?php echo $objetID; ?>'">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					Ajouter une photo
				</button>
				<button id="sautPlus" type="button" class="btn btn-info"
					aria-label="Left Align"
					onclick="document.location.href='coursListe.php'">
					<span class="glyphicon glyphicon-home" aria-hidden="true"></span>
					Revenir à la liste des cours
				</button>
			</div>
		</div>

		<div class="row">




			<div class="col-xs-12">
				<div class="table-responsive">



					<table class="table table-striped">

		   <?php
    echo "<tr><th>Numéro</th><th>Fichier</th><th>Légende</th><th>Action</th></tr>";

    $photos = DB::query("SELECT * FROM photos where objetID=%i", $objetID );

    foreach ($photos as $photo) {

        echo "<tr><td>" . $photo['id'] . "</td><td><a href='../uploads/" .  $photo['image'] ."'" . $photo['image'] . "'><img width='60px' src='../uploads/" .  $photo['image'] ."'" . $photo['image'] . "</a></td><td>" . $photo['legende'] . "</td><td>

    <button id='sautPlus' type='button' class='btn btn-danger' aria-label='Left Align' onclick=\"document.location.href='bd_delete_photos.php?objetID=" .  $objetID. "&id=" . $photo['id'] . "'\">
					<span class='glyphicon glyphicon-remove' ></span>
					Supprimer cette photo
				</button>

             </td></tr>";
    }
    ?>
            </table>

				</div>
			</div>






		</div>
	</div>
</body>
</html>