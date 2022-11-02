<?php 
include ("init.php");

$sm_contact = "active";

?>

<!DOCTYPE html>

<html>

<head>

	<?php include ("inc_header.php"); ?>

	<style type="text/css">
		body {

			background: black url(images/bgmax/BckgroundVIOLET.jpg) repeat-x top center;

			background-attachment: fixed;

		}
	</style>

</head>

<body>

	<?php

include ("inc_logo.php");

include ("inc_navigation.php");

?>



	<DIV class="container" style="margin-top: 40px;">

		<DIV class="row">

			<DIV class="col col-sm-10  col-xs-12">

				<h1>Votre panier</h1>



				<?php

                $achats = DB::query("SELECT *, count(*) as nb, commandes.Prix as prixfinal, packs.ID as PID FROM commandes

                INNER JOIN packs ON ( commandes.PAck = packs.ID )

                INNER JOIN prestations ON ( prestations.ID = packs.PrestationID )

                WHERE Session=%s

                GROUP BY Pack

                ", session_id());





                $nombre = DB::count();

                if ($nombre>1) { $pluriel="s";} else { $pluriel=""; }



                $supertotal=0;

                foreach( $achats as $achat) {



                    $total = $achat["nb"] * $achat["prixfinal"];

                    $supertotal += $total;

                    print "<div>" . $achat["nb"] . "x " .  $achat["Libelle"] . " " . $achat["Article"] . " à " .   " CHF " . $achat["prixfinal"].".- <b>" . "total  CHF " . $total . ".-</b> <span style='color:red;cursor:pointer;'

                    onclick=\"window.location.href='enleverPanier.php?pack=" . $achat["PID"] . "'\"

                    >Retirer élément</span></div>";

                }



                print "<br />";

                print "<b>TOTAL DU PANIER : CHF " . $supertotal . ".-</b>";



                ?>

				<div style="margin-top:50px;">

					<button onclick="

                    $('#f1').css('display','block');

                    $(this).css('display','none');

                    " class="btn btn-success">Valider ma commande</button>

				</div>

				<button style="margin-top:15px;" onclick="window.location.href='texte.php';"
					class="btn btn-info">Conditions générales de vente</button>

			</div>

		</DIV>

	</DIV>













	<DIV class="row" id="f1" style="display:none;">

		<DIV class="col col-sm-10  col-xs-12">



			<FORM class="form-horizontal" style="margin-top: 20px;" method="post" action="action_validate.php">



				<INPUT name="total" type="hidden" class="form-control" id="inputDate" placeholder="hidden"
					value="<?php echo $supertotal; ?>">



				<DIV class="form-group">

					<LABEL for="" class="col-sm-4 control-label">Nom <span style='color:red'>*</span></LABEL>

					<DIV class="col-sm-8">

						<INPUT type="text" name="nom" class="form-control" id="inputNom" placeholder="Nom"
							required="true">

					</DIV>

				</DIV>

				<DIV class="form-group">

					<LABEL for="" class="col-sm-4 control-label">Prénom <span style='color:red'>*</span></LABEL>

					<DIV class="col-sm-8">

						<INPUT type="text" name="prenom" class="form-control" id="nuputNom" placeholder="Prénom"
							required="true">

					</DIV>

				</DIV>

				<DIV class="form-group">

					<LABEL for="inputEmail3" class="col-sm-4 control-label">Email <span
							style='color:red'>*</span></LABEL>

					<DIV class="col-sm-8">

						<INPUT type="email" name="email" class="form-control" id="inputEmail" placeholder="Email"
							required="true">

					</DIV>

				</DIV>

				<DIV class="form-group">

					<LABEL for="inputPassword3" class="col-sm-4 control-label">Téléphone <span
							style='color:red'>*</span></LABEL>

					<DIV class="col-sm-8">

						<INPUT name="telephone" type="text" class="form-control" id="inputDate" placeholder="Téléphone"
							required="true">

					</DIV>

				</DIV>

				<DIV class="form-group">

					<LABEL for="inputPassword3" class="col-sm-4 control-label">Adresse</LABEL>

					<DIV class="col-sm-8">

						<INPUT name="adresse" type="text" class="form-control" id="inputDate" placeholder="Adresse">

					</DIV>

				</DIV>



				<DIV class="form-group">

					<LABEL for="inputPassword3" class="col-sm-4 control-label">Code postal <span
							style='color:red'>*</span></span></LABEL>

					<DIV class="col-sm-8">

						<INPUT name="cp" type="text" class="form-control" id="inputDate" placeholder="Code postal"
							required="true">

					</DIV>

				</DIV>

				<DIV class="form-group">

					<LABEL for="inputPassword3" class="col-sm-4 control-label">Localité <span
							style='color:red'>*</span></LABEL>

					<DIV class="col-sm-8">

						<INPUT name="localite" type="text" class="form-control" id="inputDate" placeholder="Localité"
							required="true">

					</DIV>

				</DIV>

				<DIV class="form-group">

					<DIV class="col-sm-offset-4 col-sm-5">

						<input type="submit" class="btn btn-default" value="Envoyer"></input>

						<br /><br /><span style='color:red'>*</span> = réponse requise

					</DIV>

				</DIV>

			</FORM>





		</DIV>



	</DIV>















	<?php include "inc_footer.php"; ?>

</body>

</html>