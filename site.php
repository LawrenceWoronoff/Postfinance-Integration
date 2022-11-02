<?php include ("init.php");
$sm_accueil = "active";
?>
<!DOCTYPE html>
<HTML>
<HEAD>
	<?php include ("inc_header.php"); ?>
	<style type="text/css">
	body {
		<?php
		$fond = DB::queryFirstRow("SELECT * FROM fonds where id=%i", 1 );
		if ($fond["image"] =="") {
			?>

			background: black url(images/bgmax/ROUGE.jpg) repeat-x top center;
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
	<?php //include_once("analyticstracking.php") ?>
	<?php
	include ("inc_logo.php");
	include ("inc_navigation.php");
	?>


	<DIV class="container" style="margin-top: 40px;margin-bottom: 40px;">
		<DIV class="row">
			<DIV class="col col-xs-12">

				<DIV id="carousel-example-generic" class="carousel slide"
				data-ride="carousel">
				<!-- Indicators -->
				<OL class="carousel-indicators">
					<LI data-target="#carousel-example-generic" data-slide-to="0" class="active"></LI>
					<LI data-target="#carousel-example-generic" data-slide-to="1"></LI>
					<LI data-target="#carousel-example-generic" data-slide-to="2"></LI>
					<LI data-target="#carousel-example-generic" data-slide-to="3"></LI>
					<LI data-target="#carousel-example-generic" data-slide-to="4"></LI>
				</OL>

				<!-- Wrapper for slides -->
				<DIV class="carousel-inner" role="listbox">
					<DIV class="item active">
						<IMG style="width: 100%"
						src="images/sliderhome/misso_home_pic1.jpg" alt="">
						<DIV class="carousel-caption"></DIV>
					</DIV>
					<DIV class="item">
						<IMG style="width: 100%"
						src="images/sliderhome/misso_home_pic2.jpg" alt="">
						<DIV class="carousel-caption"></DIV>
					</DIV>
					<DIV class="item">
						<IMG style="width: 100%"
						src="images/sliderhome/misso_home_pic3.jpg" alt="">
						<DIV class="carousel-caption"></DIV>
					</DIV>						<DIV class="item">
						<IMG style="width: 100%"
						src="images/sliderhome/misso_home_pic4.jpg" alt="">
						<DIV class="carousel-caption"></DIV>
					</DIV>						<DIV class="item">
						<IMG style="width: 100%"
						src="images/sliderhome/misso_home_pic5.jpg" alt="">
						<DIV class="carousel-caption"></DIV>
					</DIV>
					<div class="hidden-xs" onclick="window.open('https://goo.gl/maps/2Oj4w')" style="position:absolute;right:0px;bottom:20px; padding:20px;background-color:rgba(255,255,255,0.2)"
					>Visite virtuelle en 3D</div>
				</DIV>

				<!-- Controls -->
				<A class="left carousel-control" href="#carousel-example-generic"
				role="button" data-slide="prev"> <SPAN
				class="glyphicon glyphicon-chevron-left" aria-hidden="true"></SPAN>
				<SPAN class="sr-only">Previous</SPAN>
			</A> <A class="right carousel-control"
			href="#carousel-example-generic" role="button" data-slide="next">
			<SPAN class="glyphicon glyphicon-chevron-right" aria-hidden="true"></SPAN>
			<SPAN class="sr-only">Next</SPAN>
		</A>
		<div class="hidden-xs" onclick="window.open('https://goo.gl/maps/2Oj4w')" style="position:absolute;right:0px;bottom:20px; padding:20px;background-color:rgba(255,255,255,0.2)"
		>Visite virtuelle en 3D</div>
	</DIV>
</DIV>
<!--DIV class="col col-md-2 hidden-xs hidden-sm">
	<IMG style="float: right;" src="images/logo2.png" alt="">
</DIV-->
</DIV>
</DIV>
<DIV class="container" style="min-height: 150px;">
	<DIV class="semiheader">
		<?php
		$noArticle=2;
		$texte = DB::queryFirstRow("SELECT * FROM textes WHERE ID=%i", $noArticle );

		$id         = $texte["ID"];
		$titre      = $texte["Titre"];
		$texte      = $texte["Texte"];


		if ( substr($titre,0,3) != "***" && $texte!='') {
			?>

			<P style="font-size:2em"><?php echo $titre; ?></P>
		</DIV>

		<br />
		<div style="font-size:1.5em;">

			<?php echo nl2br($texte); ?>
		</div>
		<?php
	} else {
		?>

		<P>Bons cadeaux en ligne</P>
	</DIV>
	<br />
	Choisissez un massage, achetez-le vous recevrez un bon par e-mail imm&eacute;diatement.
	<a class="awhite"  href="massages_misso.php">Massages Misso</a>, <a class="awhite" href="selection_misso.php">S&eacute;lection Misso</a> et <a class="awhite" href="the_thailounge.php">The Thailounge</a>


	<?php
}

?>

</div>







<?php
			require('constant.php');
			?>
			<script src="./component/jquery/jquery-3.2.1.min.js"></script>
			<script>
				$(document).ready(function (e){
					$("#frmContact").on('submit',(function(e){

						e.preventDefault();
						$("#mail-status").hide();
						$('#send-message').hide();
						$('#loader-icon').show();
						$.ajax({
							url: "send.php",
							type: "POST",
							dataType:'json',
							data: {
								"nom":$('input[name="nom"]').val(),
								"telephone":$('input[name="telephone"]').val(),
								"email":$('input[name="email"]').val(),
								"prestation":$('select[name="prestation"]').val(),
								"date":$('input[name="date"]').val(),
								"heure":$('input[name="heure"]').val(),

								"g-recaptcha-response":$('textarea[id="g-recaptcha-response"]').val()},				
								success: function(response){
									$("#mail-status").show();
									$('#loader-icon').hide();
									if(response.type == "error") {
										$('#send-message').show();
										$("#mail-status").attr("class","error");				
									} else if(response.type == "message"){
										$('#send-message').hide();
										$("#mail-status").attr("class","success");							
									}
									$("#mail-status").html(response.text);	
								},
								error: function(){} 
							});
					}));
				});
			</script>

			<style>
			.label { display: inline-block; max-width: 100%; margin-bottom: 5px; font-weight: 700; color: black; font-family: 'Open Sans', sans-serif !important; font-size: 1.1em;    padding: 2px 0px; }
			.field {margin: 0 0 20px 0;}	
			div#central {margin: 40px 0px 100px 0px;}
			@media all and (min-width: 768px) and (max-width: 979px) {.content {width: 750px;}}
			@media all and (max-width: 767px) {
				body {margin: 0 auto;word-wrap:break-word}
				.content {width:auto;}
				div#central {	margin: 40px 20px 100px 20px;}
			}

			input, textarea {width:100%;padding: 15px;font-size:1em;border: 1px solid #A1A1A1;	}

			#message {  padding: 0px 40px 0px 0px; }
			#mail-status {
				padding: 12px 20px;
				width: 100%;
				display:none; 
				font-size: 1em;
				color: rgb(40, 40, 40);
			}
			.error{color: #a94442 !important; background-color: #f2dede; border-color: #ebccd1;border-radius: 5px;margin-bottom: 10px;}
			.success{background-color: #48e0a4; }
			.g-recaptcha {margin: 0 0 25px 0;}	  
		</style>
		<script src='https://www.google.com/recaptcha/api.js'></script>	
		
	






<DIV class="container" style="height: 150px;">
	<DIV class="row">
		<DIV class="col col-xs-12  ">
			<DIV class="bloc" style="position:relative">
				<!--img src="images/bol.gif" style="position:absolute;top:70px;left:10px;"-->
				<DIV class="semiheader" style="margin-bottom:30px;">
					<P>Demande de rendez-vous</P>
				</DIV>

				<form id="frmContact" action="" method="POST" novalidate="novalidate">
					<DIV class="form-group">
						<LABEL for="email" class="col-sm-4 control-label">Email</LABEL>
						<DIV class="col-sm-8">
							<INPUT type="email" name="email" class="form-control" id="email"
							placeholder="Email">
						</DIV>
					</DIV>
					<DIV class="form-group">
						<LABEL for="nom" class="col-sm-4 control-label">Nom</LABEL>
						<DIV class="col-sm-8">
							<INPUT type="text" name="nom" class="form-control" id="nom"
							placeholder="Nom">
						</DIV>
					</DIV>
					<DIV class="form-group">
						<LABEL for="telephone" class="col-sm-4 control-label">Téléphone</LABEL>
						<DIV class="col-sm-8">
							<INPUT type="text" name="telephone" class="form-control" id="telephone"
							placeholder="Votre no de téléphone">
						</DIV>
					</DIV>

					<DIV class="form-group">
						<LABEL for="date" class="col-sm-4 control-label">Date</LABEL>
						<DIV class="col-sm-8">
							<INPUT id="date" name="date" type="date" class="form-control"
							 placeholder="Date">
						</DIV>
					</DIV>
					<DIV class="form-group">
						<LABEL for="heure" class="col-sm-4 control-label">Heure</LABEL>
						<DIV class="col-sm-8">
							<INPUT type="time" name="heure" class="form-control" id="heure"
							placeholder="Heure">
						</DIV>
					</DIV>
					<DIV class="form-group">
						<LABEL for="prestation" class="col-sm-4 control-label">Prestation
						souhaitée</LABEL>
						<DIV class="col-sm-8">
							<SELECT name="prestation" id="prestation" class="form-control" >
								<?php
								$massages = DB::query("SELECT * FROM prestations
									INNER JOIN packs ON (prestations.id = packs.PrestationID)
									WHERE NOT packs.Article LIKE '%Abonnement%'");
								foreach ($massages as $massage) {
									echo "<OPTION value='" . $massage['Libelle'] . " " . $massage['Article'] .  "'>" . $massage['Libelle']  . " " . $massage['Article'] . "</OPION>";
								}

								?>

							</SELECT>
						</DIV>

					</DIV>
					<div class="form-group" style="margin-top:20px;">
						<div class="col-sm-offset-4 col-sm-8">
							<div class="g-recaptcha" data-sitekey="<?php echo SITE_KEY; ?>"></div>	
						</div>

					</div>
					<div class="form-group" style="margin-top:20px;margin-bottom:50px;">
						<div class="col-sm-offset-4 col-sm-8">
									
							<div id="mail-status"></div>			
							<button type="Submit" id="send-message" class="btn btn-primary" style="clear:both;">Envoyer votre message</button>
							<div id="loader-icon" style="display:none;"><img src="images/loader.gif" /></div>
					
							</div>
					</div>

						</form>
						
						

			</DIV>
		</DIV>
		<DIV class="col col-xs-12 ">

			<DIV class="bloc">
				<?php
				$noArticle=1;
				$texte = DB::queryFirstRow("SELECT * FROM textes WHERE ID=%i", $noArticle );

				$id         = $texte["ID"];
				$titre      = $texte["Titre"];
				$texte      = $texte["Texte"];


				?>

				<DIV class="semiheader">
					<P><?php echo $titre; ?></P>
				</DIV>

				<br />
				<br />

				<?php echo nl2br($texte); ?>
			</DIV>

<div class="semiheader">
					<P>Nos prestations</P>
				</div>


			<div class="bloc">
				<p style="font-weight:bolder">Massage thaï - nuad bo rarn</p>
				<p>Le massage thaï est unique. Il fait partie des 4 piliers de la médecine traditionnelle que sont : la nutrition, les herbes médicinales, la méditation et la spiritualité. Le thérapeute utilise les points profonds de pression et des techniques d’étirement pour réduire l'effort et pour soulager la douleur du muscle. Le traitement agit sur tous les tissus de l'organisme, de l'épiderme aux muscles profonds et aux ligaments, en passant par les vaisseaux sanguins et lymphatiques. Un massage complet du corps, sans huile.</p>
				<p style="font-weight:bolder">Aromathérapie - Massage aux huiles essentielles.</p>
				<p>Huile et Aromathérapie : La technique combine le massage à l’huile aux lignes d’énergie du corps, les Sen L’aromathérapie utilise les huiles essentielles et le massage pour stimuler l’esprit et améliorer la santé au travers du sens de l’odorat. Exercée avec une plus forte pression, elle aide à se débarrasser de tensions, stimule la circulation sanguine et lymphatique, étire le muscle et le tissu connectif.</p>
				<p style="font-weight:bolder">Massage aux herbes chaudes</p>
				<p>Le traitement alterne massage thaï traditionnel et traitement à l’aide de compresses chaudes aux herbes qui apaisent et détendent les muscles. Les herbes aromatiques pénètrent au travers des tissus et apportent leurs bienfaits au-delà du temps de massages. Les résultats sont remarquables.</p>
				<p style="font-weight:bolder">Réflexologie plantaire</p>
				<p>La méthode de massage des pieds combine la connaissance des points de réflexologie chinoise et une technique particulièrement relaxante. Le massage thaï débute par la plante du pied gauche, pour stimuler l’élément vent et l’équilibrer avec les 3 autres : la terre, l’eau et le feu. La pression sur les points “reflex” du pied permet de rétablir la circulation d’énergie au travers de tout le corps.</p>
				<p style="font-weight:bolder">Massage du dos</p>
				<p>Avec le stress quotidien qui nous entoure, rien n'est plus salutaire qu’un bon massage du dos. Qu'il s'agisse d'une simple friction des épaules ou d’un massage plus profond du haut et du bas du dos, ce type de massage soulage les tensions et favorise la relaxation</p>
				<p style="font-weight:bolder">Massage antistress</p>
				<p>Massage et application d’huiles essentielles sur le dos, la nuque et les épaules selon la méthode thaïlandaise. Excellent après de longues heures au bureau ou une journée harassante. Massage salutaire et rafraîchissant pour les zones du corps souvent sous pression.</p>



			</div>


		</DIV>

	</DIV>

</DIV>

<?php include "inc_footer.php"; ?>
</BODY>
</HTML>