<?php
  $domain = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']);
  if (preg_match('/tizoo/', $domain)) {
    $webmail = 'http://' . $domain . '/webmail';
    $cpanel = 'http://' . $domain . '/cpanel';
  }
  else {
    $webmail = 'http://webmail.' . $domain;
    $cpanel = 'http://cpanel.' . $domain;
  }
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>TiZoo | Hébergement</title>
  <style type="text/css">
  <!--
  h3 {
    color: #07123F;
    padding: 0.2em;
    display: inline;
  }
  h4 {
    color: #07123F;
    padding: 0.2em;
    display: inline;
  }
  P {
    text-align: center;
  }
  A {
    text-decoration: none;
    color: #FF9D00;
  }
  .textLeft {
    width: 49%;
    text-align: right;
  }
  .textCenter {
    width: 2%;
    text-align: center;
  }
  .textRight {
    width: 49%;
    text-align: left;
  }
  .footer {
    font-size: 9px;
    text-align: center;
    text-transform: uppercase;
  }
  .upperCase {
    text-transform: uppercase;
  }
  -->
</style>
</head>
<body style="font-family: arial, sans-serif;">
<br><br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
  <td class="textLeft"><h3>Votre compte est en ligne</h3></td>
  <td class="textCenter"><h3>|</h3></td>
  <td class="textRight"><h3>Your account is online</h3></td>
</tr>
<tr style="height:30px;"><td colspan="3"></td></tr>
<tr>
  <td colspan="3" style="text-align:center;">
    <img alt="H&eacutebergement Web TiZoo" usemap="#tizooMap" src="http://www.tizoo.com/img/logo-hosting_suspended.png" style="border:none;">
    <map name="tizooMap">
      <area coords="0,0,118,118" shape="rect" alt="Accès à la page d'entrée du site" href="http://www.tizoo.com"/>
      <area coords="130,0,249,118" shape="rect" alt="Accès aux offres d'hébergement internet" href="http://www.tizoo.com/fr/hebergement.html"/>
    </map>
    <p style="color: #aaa; font-size: 8pt; padding-top:0px;">
      AlpHosting, TiZoo Sàrl, Marnière 29, 2068 Hauterive<br>
      ©Copyright 2001-2009 TiZoo Sàrl - All Rights Reserved.
    </p>
  </td>
</tr>
<tr style="height:30px;"><td colspan="3"></td></tr>
<tr>
  <td class="textLeft"><h4><a href="<?php echo $webmail; ?>">Acc&eacute;der à votre Webmail</a></h4></td>
  <td class="textCenter"><h4>|</h4></td>
  <td class="textRight"><h4><a href="<?php echo $webmail; ?>">Access your Webmail</a></h4></td>
</tr>
<tr style="height:5px;"><td colspan="3"></td></tr>
<tr>
  <td class="textLeft"><h4><a href="<?php echo $cpanel; ?>">Acc&eacute;der à votre Panneau de Contr&ocirc;le</a></h4></td>
  <td class="textCenter"><h4>|</h4></td>
  <td class="textRight"><h4><a href="<?php echo $cpanel; ?>">Access your Control Panel</a></h4></td>
</tr>
<tr style="height:15px;"><td colspan="3"></td></tr>
<tr><td colspan="3" class="footer">|
  <a title="hebergement web" alt="hebergement web" href="http://www.tizoo.com/fr/hebergement.html">H&eacute;bergement Web</a> |
  <a title="hebergement joomla" alt="hebergement joomla" href="http://www.tizoo.com/fr/hebergement/host/hebergement-joomla.html">H&eacute;bergement Joomla</a> |
  <a title="hebergement java" alt="hebergement java" href="http://www.tizoo.com/fr/hebergement/host/expert.html">H&eacute;bergement Java</a> |
  <a title="hebergement ruby" alt="hebergement ruby" href="http://www.tizoo.com/fr/hebergement/host/expert.html">H&eacute;bergement Ruby</a> |
  <a title="hebergement ssh" alt="hebergement ssh" href="http://www.tizoo.com/fr/hebergement/host/expert.html">H&eacute;bergement ssh</a> |
  <a title="formation linux" alt="formation linux" href="http://www.tizoo.com/fr/formation/systeme/linux-bases.html">Formation Linux</a> |
</td></tr>
</table>



</body>
</html>

