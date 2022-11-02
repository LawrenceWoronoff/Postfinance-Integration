<?php

include "inc_top_admin.php";

function upload($index,$destination,$maxsize=FALSE,$extensions=FALSE)

{
	if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
	if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
	$ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
	if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;
	return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
}





$objetID            = $_POST["objetID"];

$image =    $_FILES['image']['name']; 
$i2 =       $_FILES['image']['type'];  
$i3 =       $_FILES['image']['size'];     //La taille du fichier en octets.
$i4 =       $_FILES['image']['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
$i5 =       $_FILES['image']['error'];

$extension = substr($image, -4);

$imageb = $objetID . $extension;

//echo $imageb;

$upload1 = upload('image',"../images/fonds/$image",1000000, array('png','gif','jpg','jpeg') );


DB::update('fonds', array(
	'image'     => "$image"
),  "id=%i", $objetID );

header("location:bgListe.php");
exit();
?>