<?php include ("init.php");
$sm_contact = "active";
?>
<HTML>
<HEAD>
	<?php include ("inc_header.php"); ?>
	<style type="text/css">
	
	body {
		<?php
		$fond = DB::queryFirstRow("SELECT * FROM fonds where id=%i", 6 );
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
	<?php
	include ("inc_logo.php");
	include ("inc_navigation.php");
	?>

	<DIV class="container" style="margin-top: 40px;">
		<DIV class="row">
			<DIV class="col col-sm-10  col-xs-12">

				<FORM class="form-horizontal" style="margin-top: 20px;" action="sendmail.php" method="post">

					<DIV class="form-group">
						<LABEL for="inputPassword3" class="col-sm-4 control-label">Nom</LABEL>
						<DIV class="col-sm-8">
							<INPUT type="text" name="nom"  class="form-control" id="nuputNom"
							placeholder="Nom">
						</DIV>
					</DIV>
					<DIV class="form-group">
						<LABEL for="inputPassword3" class="col-sm-4 control-label">Prénom</LABEL>
						<DIV class="col-sm-8">
							<INPUT type="text" name="prenom" class="form-control" id="nuputNom" placeholder="Prénom">
						</DIV>
					</DIV>
					<DIV class="form-group">
						<LABEL for="inputEmail3" class="col-sm-4 control-label">Email</LABEL>
						<DIV class="col-sm-8">
							<INPUT type="email" name="email"  class="form-control" id="inputEmail" placeholder="Votre adrese mail">
						</DIV>
					</DIV>
					<DIV class="form-group">
						<LABEL for="inputPassword3" class="col-sm-4 control-label">Téléphone</LABEL>
						<DIV class="col-sm-8">
							<INPUT name="telephone" id="telephone" type="text" class="form-control"
							placeholder="Téléphone">
						</DIV>
					</DIV>

					<DIV class="form-group">
						<LABEL for="inputPassword3" class="col-sm-4 control-label">Votre message</LABEL>
						<DIV class="col-sm-8">

							<TEXTAREA id="message"  class="form-control"
							name="message" placeholder="Votre message"></TEXTAREA>
						</DIV>
					</DIV>
					<DIV class="form-group">
						<DIV class="col-sm-offset-4 col-sm-5">
							<BUTTON type="submit" class="btn btn-default">Envoyer</BUTTON>
						</DIV>
					</DIV>
				</FORM>

				<div id="cartu" style="color: black; font-family: poiret,arial;">
					<!-- Plugin Google Maps version 3.2 by Mike Reumer --><!-- fail nicely if the browser has no Javascript -->
					<noscript><blockquote class='warning'><p><b>JavaScript doit être activé pour utliser les cartes Google.</b> <br/>Il semble que JavaScript soit désactivé ou non supporté sur votre navigateur. <br/>Pour voir les cartes Google Maps, activez JavaScript en changeant les options de votre navigateur, puis rechargez la page.</p></blockquote></noscript><div id='mapbody3_gi9so_0' style="display: none; text-align:center"><div id="googlemap3_gi9so_0" class="map" style="margin-right: auto; margin-left: auto; width:500px; height:400px;"></div></div>
					<script type='text/javascript'>/*<![CDATA[*/
					google.maps.visualRefresh = false;
					var mapconfig3_gi9so_0 = {"debug":"0","show":"1","mapclass":"","loadmootools":"1","timeinterval":"500","googlewebsite":"maps.google.com","align":"center","width":"500px","height":"400px","effect":"none","deflatitude":"52.075581","deflongitude":"4.541513","centerlat":"","centerlon":"","address":"","latitudeid":"","latitudedesc":"1","latitudecoord":"0","latitudeform":"0","controltype":"UI","zoomtype":"3D-large","svcontrol":"1","returncontrol":"1","zoom":"17","corzoom":"0","minzoom":"0","maxzoom":"19","rotation":"1","zoomnew":"0","zoomwheel":"0","keyboard":"0","maptype":"Normal","showmaptype":"1","shownormalmaptype":"1","showsatellitemaptype":"1","showhybridmaptype":"1","showterrainmaptype":"1","showearthmaptype":"1","showscale":"0","overview":"0","dragging":"1","marker":"1","traffic":"0","transit":"0","bicycle":"0","panoramio":"0","panominzoom":"none","panomaxzoom":"none","pano_userid":"","pano_tag":"","weather":"0","weathercloud":"0","weatherinfo":"1","weathertempunit":"celsius","weatherwindunit":"km","dir":"0","dirtype":"D","formdirtype":"1","avoidhighways":"0","avoidtoll":"0","diroptimize":"0","diralternatives":"0","showdir":"1","animdir":"0","animspeed":"1","animautostart":"0","animunit":"kilometers","formspeed":"0","formaddress":"0","formdir":"0","autocompl":"both","txtdir":"Directions: ","txtgetdir":"Get Directions","txtfrom":"From here","txtto":"To here","txtdiraddr":"Address: ","txt_driving":"","txt_avhighways":"","txt_avtoll":"","txt_walking":"","txt_bicycle":"","txt_transit":"","txt_optimize":"","txt_alternatives":"","dirdefault":"0","gotoaddr":"0","gotoaddrzoom":"0","txtaddr":"Address: ##","erraddr":"Address ## not found!","txtgotoaddr":"Goto","clientgeotype":"google","lightbox":"0","txtlightbox":"Open lightbox","lbxcaption":"","lbxwidth":"500px","lbxheight":"700px","lbxcenterlat":"","lbxcenterlon":"","lbxzoom":"","sv":"none","svpano":"","svwidth":"100%","svheight":"300px","svautorotate":"0","svaddress":"1","kmlrenderer":"google","kmlsidebar":"none","kmlsbwidth":"200px","kmllightbox":"0","kmlhighlite":"{ \"color\": \"#aaffff\", \"opacity\": 0.3,  \"textcolor\": \"#000000\" }","proxy":"0","tilelayer":"","tilemethod":"","tileopacity":"1","tilebounds":"","tileminzoom":"0","tilemaxzoom":"19","twittername":"","twittertweets":"15","twittericon":"\/media\/plugin_googlemap3\/site\/Twitter\/twitter_map_icon.png","twitterline":"#ff0000ff","twitterlinewidth":"4","twitterstartloc":"0,0,0","lang":"fr-FR","mapType":"map","geocoded":0,"tolat":"","tolon":"","toaddress":"","description":"&lt;div style=\'width:200px; height:80px;\'&gt;Massage missO&lt;br \/&gt;Maupas 27&lt;br \/&gt;&nbsp; du lundi au samedi &lt;br \/&gt; de&nbsp; 10h &agrave; 19h &lt;\/div&gt;","tooltip":"Misso","kml":[],"kmlsb":[],"layer":[],"camera":[],"searchtext":"","latitude":"46.5249751","longitude":"6.625256","waypoints":[],"mapnm":"3_gi9so_0","largeur":"100%","hauteur":"300px","descr":"1","geoxmloptions":{"titlestyle":" class=kmlinfoheader ","descstyle":" class=kmlinfodesc ","veryquiet":true,"quiet":true,"nozoom":true,"iwmethod":"click","sortbyname":null,"linktarget":"_self","linkmethod":"dblclick","hilite":{"color":"#aaffff","opacity":0.3,"textcolor":"#000000"},"lang":{"txtdir":"Directions: ","txtto":"To here","txtfrom":"From here","txtsrchnrby":"Search nearby","txtzoomhere":"Zoom Here","txtaddrstart":"Start address:","txtgetdir":"Go","txtback":"\u00ab Back","txtsearchnearby":"Search nearby: e.g. pizza","txtsearch":"Go"},"inputsize":"25"},"icontype":"","earthoptions":{"timeout":"300","borders":true,"buildings":false,"roads":false,"terrain":false,"lookat":[],"camera":[]}};
					var mapstyled3_gi9so_0 = null;
					var googlemap3_gi9so_0 = new GoogleMaps('3_gi9so_0', mapconfig3_gi9so_0, mapstyled3_gi9so_0);
					/*]]>*/</script></div>
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