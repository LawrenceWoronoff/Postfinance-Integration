<?php
?>
<DIV class="container divfooter" style="margin-top: 90px; clear: both;">
<DIV class="row">
<DIV class="col col-xs-12 col-sm-4 ">
                <DIV class="handy" onclick="window.location.href='site.php'">Accueil</DIV>
				<DIV class="handy"  onclick="window.location.href='massages_misso.php'">Massage Misso</DIV>
				<DIV class="handy"  onclick="window.location.href='selection_misso.php'">Sélection Misso</DIV>
				<DIV class="handy"   onclick="window.location.href='the_thailounge.php'">The thailounge</DIV>
				<DIV class="handy"   onclick="window.location.href='presse.php'">Presse</DIV>
				<DIV class="handy"   onclick="window.location.href='contact.php'">Contact</DIV>
				<DIV class="handy"   onclick="window.location.href='texte.php'">Conditions générales de vente (CG)</DIV>
</DIV>
<br />



				<DIV style="padding-top:10px; text-align:center; " class="col col-xs-12 col-sm-4 adresse">

				<?php

				$temoignages = DB::query("SELECT * FROM temoignages");
				$nombre = DB::count();
				$alea = rand(1,$nombre);
                print "<b >Témoignages</b><br />";
				foreach ($temoignages as $temoignage) {
				    $i++;
				    if ($i== $alea) {
				    print  $temoignage["Texte"];
				    print "<br />" . $temoignage["Auteur"];
				    }
                }




				?>


</DIV>





<DIV class="col col-xs-12 col-sm-4 adresse">
<P>
+41 21 641 27 81 <BR />Maupas 27 - Lausanne <BR />Lundi au samedi
10h-19h. <BR /><a style="font-weight:bolder;color:#fff;" href="mailto:thaistyle@massagemisso.ch">thaistyle@massagemisso.ch</a>
</P>
</DIV>
</DIV>
</DIV>
<br />
