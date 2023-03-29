<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
ob_start();

$sessionID = session_id();

include 'lib/php/mysql.class.php';

include 'lib/php/config.php';

$sm_accueil     =   "";

$sm_massage     =   "";

$sm_selection   =   "";

$sm_thailounge  =   "";

$sm_presse      =   "";

$sm_contact     =   "";

$sm_enduo     	=   "";

$baseurl = sprintf(     // In main server, we will remove 'massage' in URL
    "%s://%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME']
  ). '/testpfc';

?>