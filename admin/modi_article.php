<?php
include "inc_top_admin.php";



$titre = "Modifier un article";



$id = $_GET["id"];


$massage = DB::queryFirstRow("SELECT * FROM articles WHERE ID=%i", $id);

$id         = $massage["ID"];
$libelle    = $massage["Libelle"];
$intro      = $massage["Intro"];
$texte      = $massage["Texte"];
$categorie  = $massage["Categorie"];
$ordre      = $massage["Ordre"];



?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=2">
<meta name="description" content="">
<meta name="author" content="">


<title>Misso - les massages</title>


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


<form method="POST" action="bd_modi_article.php">
<input name="id" type="hidden" class="form-control" value="<?php  echo $id; ?>">




<div class="form-group">
<label for="exampleInputEmail1">Nom</label>
<input name="libelle" type="text" class="form-control" id="exampleInputEmail1"  value="<?php echo $libelle; ?>">
</div>
<div class="form-group">
<label for="exampleInputEmail1">Texte d'intro</label>
<textarea name="intro" type="text" class="form-control" id="exampleInputEmail1"><?php echo $intro ?></textarea>
</div>
<div class="form-group">
<label for="exampleInputEmail1">Texte</label>
<textarea  name="texte" type="text" class="form-control" id="exampleInputEmail1"><?php echo $texte; ?></textarea>
</div>
<div class="form-group">
<label for="exampleInputEmail1">Catégorie</label>
<input  name="categorie" type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $categorie; ?>">
</div>
<div class="form-group">
<label for="exampleInputEmail1">Ordre</label>
<input  name="ordre" type="text" class="form-control" id="exampleInputEmail1" value="<?php echo $ordre; ?>">
</div>


 <button type="submit" class="btn btn-success">Envoyer</button>
 </form>
	</div>
	</div>
	<br />
	<br />
</body>
</html>