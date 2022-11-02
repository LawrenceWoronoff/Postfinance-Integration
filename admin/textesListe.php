<?php
$cours = "active";
session_start();

include '../lib/php/mysql.class.php';
include '../lib/php/config.php';

if (! isset($_SESSION["user"])) {
    //header("location:login.php");
}

if ($_SESSION["level"] != 2) {
    //header("location:../index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=2">
<meta name="description" content="">
<meta name="author" content="">


<title>MISSO les témoignages</title>


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
<!--

	<div class="container">

		<div class="row" style="margin-bottom: 30px">
			<div class="col-xs-12  text-center">
				<button id="sautPlus" type="button" class="btn btn-success"
					aria-label="Left Align"
					onclick="$('#new').css('display','block');">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					Ajouter un témoignage
				</button>
			</div>
			</div>

		</div>






		<div class="container" id="new" style="display:none;">
	<div class="row">
	<div class="col-xs-12  text-center">
	<h1>Ajouter un témoignage</h1>
	</div>
	</div>
	<div class="row">

<form method="POST" action="bd_new_massage.php">

<div class="form-group">
<label for="exampleInputEmail1">A</label>
<input name="texte" type="text" class="form-control" id="exampleInputEmail1" placeholder="Libell� du témoignage">
</div>

<div class="form-group">
<label for="exampleInputEmail1">Date</label>
<input name="date" type="date" class="form-control" id="exampleInputEmail1" placeholder="Date du témoignage">
</div>

<div class="form-group">
<label for="exampleInputEmail1">Auteur</label>
<input name="auteur" type="text" class="form-control" id="exampleInputEmail1" placeholder="Texte du témoignage">
</div>
<div class="form-group">
<button type="submit" class="btn btn-success">Enregistrer</button></div>
</form>
</div>
</div>







 -->









		<div class="container">

	<div class="row">

						<table class="table table-striped">

		   <?php
    echo "<tr><th>N°</th><th>Titre</th><th>Action</th></tr>";

$textes = DB::query("SELECT * FROM textes");
		foreach ( $textes as $texte ) {


		        echo "<tr><td>"
                . $texte['ID']
		        . "</td><td>"
                . $texte['Titre']
		        . "</td><td>


    <button id='sautPlus' type='button' class='btn btn-info' aria-label='Left Align' onclick=\"document.location.href='modi_textes.php?id=" . $texte['ID'] . "'\">
					<span class='glyphicon glyphicon-edit' ></span>
					Modifier ce texte
				</button>



             </td></tr>";
		    }
		    ?>
		                </table>

</div>
</div>

</body>
</html>