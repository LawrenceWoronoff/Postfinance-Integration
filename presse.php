<?php include ("init.php");
$sm_presse = "active";
?>
<HTML>
<HEAD>
	<?php include ("inc_header.php"); ?>
	<style type="text/css">
	body {
		<?php
		$fond = DB::queryFirstRow("SELECT * FROM fonds where id=%i", 5 );
		if ($fond["image"] =="") {
			?>

			background: black url(images/bgmax/BckgroundVIOLET.jpg) repeat-x top center;
			background-attachment:fixed;

			<?php
		} else {
			?>
			background: black url(images/fonds/<?php echo  $fond["image"]; ?>) repeat-x top center;
			background-attachment:fixed;

			<?php
		}
		?>
	}


</style>
</HEAD>
<BODY>
	<?php include_once("analyticstracking.php") ?>
	<?php
	include ("inc_logo.php");
	include ("inc_navigation.php");
	?>

	<DIV class="container" style="margin-top: 40px;">
		<DIV class="row">
			<DIV class="col col-sm-11  col-xs-12">
















				<?php
				$massages = DB::query( "SELECT * FROM articles  ORDER BY Ordre" );

				foreach ($massages as $massage) {

					?>

					<DIV class="row" style="margin-top:30px;">
						<DIV class="col col-md-6 col-xs-12 ">
							<DIV class="imagemass" style="position:relative">
								<img class="img-responsive" src="images/presse/<?php echo $massage["ID"];  ?>.jpg" alt="" />
								<P><?php echo $massage["Libelle"];  ?></P>
							</DIV>


						</DIV>
						<DIV class="col col-md-6 col-xs-12" style="padding-top:2px;">


							<DIV class="row">
								<DIV class="presselegende" id="t1"><?php echo $massage["Intro"];  ?><p style="cursor:pointer;">Lire l'article</p></DIV>
								<DIV class="handy pressedescription" id="ft1"><?php echo nl2br($massage["Texte"]);  ?></DIV>
							</DIV>

						</DIV>
					</DIV>



					<?php
				}
				?>
			</DIV>
			<DIV class="col col-sm-1 hidden-xs">
				<IMG style="float: right;" src="images/logo2.png" alt="">
			</DIV>
		</DIV>

	</DIV>

	<?php include "inc_footer.php"; ?>
</BODY>
</HTML>