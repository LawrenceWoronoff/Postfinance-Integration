<?php
include "inc_top_admin.php";
$objetID=$_GET["objetID"];

$titre = "Ajouter une fac similé";





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
	<h1>Ajouter une fac similé</h1>
	</div>


	<div class="row">

<form method="POST" action="bd_new_facsimiles.php" enctype="multipart/form-data">
<input name="objetID" type="hidden" class="form-control" id="exampleInputEmail1" value="<?php echo $objetID; ?>">

<div class="form-group">
<label for="exampleInputEmail1">Image</label>
    <input type="file" name="image" id="image" /><br />
     <input type="hidden" name="MAX_FILE_SIZE" value="10048576" />
</div>
	<div style="color:red">
		  ATTENTION :  les noms des fichiers ne doivent comporter ni accents, ni caracatères spéciaux ou espaces.
		</div>

 <button type="submit" class="btn btn-success">Submit</button>
 </form>
	</div>
	</div>
</body>
</html>