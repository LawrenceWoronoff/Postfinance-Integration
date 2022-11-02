<?php
include "inc_top_admin.php";



$titre = "Modifier un pack";



$id = $_GET["id"];
$massageid = $_GET["massageid"];


$pack = DB::queryFirstRow("SELECT * FROM packs WHERE ID=%i", $id);

$id             = $pack["ID"];
$article        = $pack["Article"];
$prix           = $pack["Prix"];
$prixspecial    = $pack["PrixSpecial"];




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


<form method="POST" action="bd_modi_pack.php">
<input name="id" type="hidden" class="form-control" value="<?php  echo $id; ?>">
<input name="massageid" type="hidden" class="form-control" value="<?php  echo $massageid; ?>">

<div class="form-group">
<label for="exampleInputEmail1">Article</label>
<input name="article" type="text" class="form-control" id="exampleInputEmail1"  value="<?php echo $article; ?>">
</div>
<div class="form-group">
<label for="exampleInputEmail1">Prix</label>
<textarea name="prix" type="text" class="form-control" id="exampleInputEmail1"><?php echo $prix?></textarea>
</div>
<div class="form-group">
<label for="exampleInputEmail1">Prix Spécial</label>
<textarea  name="prixspecial" type="text" class="form-control" id="exampleInputEmail1"><?php echo $prixspecial; ?></textarea>
</div>


 <button type="submit" class="btn btn-success">Envoyer</button>
 </form>
	</div>
	</div>
	<br />
	<br />
</body>
</html>