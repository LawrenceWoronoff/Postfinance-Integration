<?php include ("init.php");
$sm_cg = "active";
?>
<HTML>
<HEAD>
<?php include ("inc_header.php"); ?>
<style type="text/css">
body {
	background: black url(images/bgmax/BckgroundVIOLET.jpg) repeat-x top center;
	background-attachment:fixed;
}</style>
</HEAD>
<BODY>
<?php
include ("inc_logo.php");
include ("inc_navigation.php");
?>

	<DIV class="container" style="margin-top: 40px;">
		<DIV class="row">
			<DIV class="col col-sm-10  col-xs-12">
<?php
$noArticle=3;
				$texte = DB::queryFirstRow("SELECT * FROM textes WHERE ID=%i", $noArticle );

				$id         = $texte["ID"];
				$titre      = $texte["Titre"];
				$texte      = $texte["Texte"];
                print "<h2>" . $titre . "</h2>";
				print nl2br($texte);

				?>
<div style="color: black; font-family: poiret,arial;">&nbsp;</div>

			</DIV>
			<DIV class="col col-sm-2 hidden-xs">
				<IMG style="float: right;" src="images/logo2.png" alt="">
			</DIV>
		</DIV>

	</DIV>

<?php include "inc_footer.php"; ?>
</BODY>
</HTML>