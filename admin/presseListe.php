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


<title>MISSO les massages</title>


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
					onclick="$('#new').css('display','block');">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					Ajouter un article
				</button>
			</div>
			</div>

		</div>






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
<input name="libelle" type="text" class="form-control" id="exampleInputEmail1" placeholder="Libellé du massage">
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
    echo "<tr><th>Journal</th><th>Titre</th><th>Action</th></tr>";

$massages = DB::query("SELECT * FROM articles");
		foreach ($massages as $massage) {


		        echo "<tr><td>" . $massage['Libelle']

		        . "</td><td>

        <button id='sautPlus' type='button' class='btn btn-primary' aria-label='Left Align' onclick=\"document.location.href='new_photos.php?objetID=" . $massage['ID'] . "'\">
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
				</button>

             </td></tr>";
		    }
		    ?>
		                </table>

</div>
</div>


</body>
</html>