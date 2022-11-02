<?php
include "inc_top_admin.php";

$id                 = $_GET["id"];


DB::delete('packs', "ID=%i", $id );

header("location:massageListe.php"  );
exit();