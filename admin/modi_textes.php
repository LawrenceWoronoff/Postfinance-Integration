<?php
include "inc_top_admin.php";



$titre = "Modifier un texte";



$id = $_GET["id"];


$texte = DB::queryFirstRow("SELECT * FROM textes WHERE ID=%i", $id);

$id         = $texte["ID"];
$titre      = $texte["Titre"];
$texte      = $texte["Texte"];




?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=2">
<meta name="description" content="">
<meta name="author" content="">


<title>Misso - les textes du site</title>


<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">


<style type="text/css">
.color-red {
    color:red;
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
	<h1><?php echo $titre; ?></h1>
	</div>
	<div class="row">


<form method="POST" action="bd_modi_texte.php">
<input name="id" type="hidden" class="form-control" value="<?php  echo $id; ?>">

<div class="form-group">
<label for="exampleInputEmail1">Titre</label>
<input name="titre" type="text" class="form-control" id="exampleInputEmail1"  value="<?php echo $titre; ?>">
</div>

<div class="form-group">
<label for="exampleInputEmail1">Texte</label>
<textarea name="texte" type="text" class="form-control" id="exampleInputEmail1"><?php echo $texte ?></textarea>
</div>



 <button type="submit" class="btn btn-success">Envoyer</button>
 </form>
	</div>
	</div>
	<br />
	<br />
</body>
</html>