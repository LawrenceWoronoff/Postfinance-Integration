	<DIV class="container divlogo">
		<DIV class="row">
			<DIV style="cursor:pointer;" class="col col-xs-12 col-md-6 logo" onclick="window.location.href='site.php'">
				<!--P>
					massage miss<B>o</B>
				</P-->
			</DIV>
			<DIV class="col col-xs-12 col-md-6  adresse">
				<P>
					<span style="font-size:1.5em">+41 21 641 27 81</span> <img style="margin-left:30px;" src="./images/nosex.gif"><BR />Maupas 27 - Lausanne <BR />Lundi au samedi
					10h-19h. <BR />thaistyle@massagemisso.ch<br />


					<?php
					DB::query("SELECT * FROM commandes WHERE Session=%s", session_id());
					$nombre = DB::count();
                    if ($nombre>1) { $pluriel="s"; } else { $pluriel=""; }
                   if ($nombre>0) { $size="1.8em";  $voir="voir"; } else { $size="1em"; $voir="";}
                    ?>
                    <a style="color:white;font-size:<?php echo $size;?>;" href="panier.php">
					<?php echo $voir; ?> <span  class="glyphicon glyphicon-shopping-cart"></span><b> <?php echo $nombre; ?></b> élément<?php echo $pluriel; ?>
					 </a>
				</P>
			</DIV>
		</DIV>
	</DIV>