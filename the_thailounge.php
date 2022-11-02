<?php include ("init.php");
$sm_thailounge = "active";
?>
<HTML>
<HEAD>
	<?php include ("inc_header.php"); ?>
	<style type="text/css">

	body {
		<?php
		$fond = DB::queryFirstRow("SELECT * FROM fonds where id=%i", 4 );
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



				<DIV class="row">
					<DIV class="col col-sm-12 col-xs-12 ">
						<DIV class="imagemass" style="position:relative">
							<img class="img-responsive" src="images/thailounge/thai.jpg" alt="" />
							<P>The Thailounge</P>
						</DIV>
						<DIV class="viewlegende" id="t1">Massage et cuisine Thaï.</DIV>
						<DIV class="handy viewdescription" id="ft1">
							<p>L'espace thaistyle vous est entièrement réservé
								<p>Miss o vous propose une soirée où se succédent un massage puis un repas de dégustation dans la plus pure tradition siamoise.
									<p>Un concept unique.
										<p>Au travers de 3 menus spécialement composés, laissez-nous vous faire découvrir le pays du sourire. De 2 à 4 personnes, et durant près de 4 heures...
											<p>Sur réservation. 215.- p/pers.</DIV>






											</DIV>

										</DIV>















										<?php
										$massages = DB::query("SELECT * FROM prestations WHERE Categorie=%i ORDER BY Ordre", 3);

										foreach ($massages as $massage) {

											?>

											<DIV class="row" style="margin-top:30px;">
												<DIV class="col col-md-6 col-xs-12 prix hidden-lg hidden-md" style="padding-top:20px;margin-bottom:30px;">
													<?php
													$packs = DB::query("SELECT * FROM packs WHERE PrestationID=%i ORDER BY Prix", $massage["ID"]);
													foreach ($packs as $pack) {
														?>

														<DIV class="row">
															<DIV class="col col-xs-6"><?php echo $pack["Article"]; ?> <?php echo $pack["Prix"]; ?>.-</DIV>
															<DIV class="col col-xs-6">
																<IMG onclick="window.location.href='ajoutPanier.php?pack=<?php echo $pack["ID"]; ?>'" class="handy" src="images/picto2.png" alt="">
															</DIV>
														</DIV>
														<?php
													}
													?>
												</DIV>
												<DIV class="col col-md-6 col-xs-12 ">
													<DIV class="imagemass" style="position:relative">
														<img class="img-responsive" src="images/massages/<?php echo $massage["ID"];  ?>.jpg" alt="" />
														<P><?php echo $massage["Libelle"];  ?></P>
													</DIV>
													<DIV class="viewlegende" id="t1"><?php echo $massage["Intro"];  ?></DIV>
													<DIV class="handy viewdescription" id="ft1"><?php echo $massage["Texte"];  ?></DIV>

												</DIV>
												<DIV class="col col-md-6 col-xs-12 prix hidden-xs hidden-sm" style="padding-top:20px;">
													<?php
													$packs = DB::query("SELECT * FROM packs WHERE PrestationID=%i ORDER BY Prix", $massage["ID"]);
													foreach ($packs as $pack) {
														?>

														<DIV class="row">
															<DIV class="col col-xs-6"><?php echo $pack["Article"]; ?>





																<?php

																$prix = $pack["Prix"];
																$prixspecial = $pack["PrixSpecial"];

																if ( $prixspecial >0 ) {
																	echo "<span style='text-decoration:line-through'>" .  $prix ."" . ".-</span> <span style='color:red'>" .  $prixspecial .".-</span>";
																} else {
																	echo "" .  $prix .".-";
																}


																?>







															</DIV>
															<DIV class="col col-xs-6">
																<IMG onclick="window.location.href='ajoutPanier.php?pack=<?php echo $pack["ID"]; ?>'" class="handy" src="images/picto2.png" alt="">
															</DIV>
														</DIV>
														<?php
													}
													?>
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