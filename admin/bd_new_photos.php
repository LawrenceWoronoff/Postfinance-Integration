<?php
session_start();


function upload($index,$destination,$maxsize=FALSE,$extensions=FALSE)

{

    if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;

    if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;

    $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);

    if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;

 //   echo $destination;
    return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);

}



//EXEMPLES







/*
include '../lib/php/mysql.class.php';
include '../lib/php/config.php';
*/

/*`cours`
  `coursID` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `presentation` text NOT NULL,
  `description` text NOT NULL,
  `duree` int(11) NOT NULL,
  `prix` double NOT NULL,
  `createur` int(11) NOT NULL,
  `uniteDuree` varchar(10) NOT NULL
 */


$objetID            = $_POST["objetID"];



$image =    $_FILES['image']['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
$i2 =       $_FILES['image']['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
$i3 =       $_FILES['image']['size'];     //La taille du fichier en octets.
$i4 =       $_FILES['image']['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
$i5 =       $_FILES['image']['error'];

$extension = substr($image, -4);

$imageb = $objetID . $extension;

//echo $imageb;

$upload1 = upload('image',"../images/massages/$imageb",1000000, array('png','gif','jpg','jpeg') );

if ( $upload1 ) { echo "OK"; } else { echo "KO";  }


/*DB::insert('photos', array(
'legende'   => "$legende",
'image'     => "$image",
'objetID'   => "$objetID",
'ordre'     => "$ordre"
));*/

header("location:massagesListe.php");
?>