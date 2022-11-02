<?php
include "inc_top_admin.php";
$titre = "Photos ";
$cours ="active";

$objetID = $_GET['objetID'];

if ($_SESSION["level"]!=2) {     }
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
		<div class="row">
			<div class="col-xs-12">
				<div class="table-responsive">
					<table class="table table-striped">
						<?php
						echo "<tr><th>Numéro</th><th>Fichier</th><th>Position</th><th>Action</th></tr>";

						$photos = DB::query("SELECT * FROM fonds " );

						foreach ($photos as $photo) {

							if ( $photo['image'] !="")  {
								echo "<tr><td>" . $photo['id'] . "</td><td><a href='../images/fonds/" .  $photo['image'] ."'" . $photo['image'] . "'><img width='60px' src='../images/fonds/" .  $photo['image'] ."'" . $photo['image'] . "</a></td><td>" . $photo['position'] . "</td><td>";
							} else {
								echo "<tr><td>" . $photo['id'] . "</td><td>aucune</td><td>" . $photo['position'] . "</td><td>";

							}
							echo "<button id='sautPlus' type='button' class='btn btn-success' aria-label='Left Align' onclick=\"document.location.href='new_bg.php?objetID=" . $photo['id'] . "'\">
							<span class='glyphicon glyphicon-remove' ></span>
							Ajouter un fond
							</button>";


							echo "<button id='sautPlus' type='button' class='btn btn-danger' aria-label='Left Align' onclick=\"document.location.href='bd_delete_bg.php?objetID=" . $photo['id'] . "'\">
							<span class='glyphicon glyphicon-remove' ></span>
							Supprimer ce fond
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