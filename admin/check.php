<?php
include "inc_top_anonymous.php";

$email  = $_POST["email"];
$password   = $_POST["password"];

DB::query("SELECT * FROM personne WHERE email=%s AND password=%s", $email , $password );
$compteur = DB::count();

//echo $compteur . " " . $email . " " . $password;


$qui = DB::queryFirstRow("SELECT * FROM personne WHERE email=%s AND password=%s", $email, $password );
$_SESSION["user"] = $qui["ID"];
$_SESSION["level"] = 2; //$qui["origine"];

//echo $_SESSION["origine"];
//echo $_SESSION["user"];


if ($compteur>0) {
    if ( $_SESSION["level"]==1 ) header("location:../index.php");
    if ( $_SESSION["level"]==2 ) header("location:massagesListe.php");
    exit();

}
else {
  header("location:login.php");
    exit();
}
?>