<?php
session_start();

include '../lib/php/mysql.class.php';
include '../lib/php/config.php';
DB::debugMode();

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

$libelle            = $_POST["libelle"];




if ($libelle!='') {
DB::insert('articles', array(
'Libelle'       => "$libelle"
));
}
header("location:articlesListe.php");
?>