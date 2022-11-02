<?php

/**
 * Class de gestions des Erreur OBJET, souvant déclancher automatiquement pars les class php5
 * @author Tomasina pierre - Meta-Gen.com  (sora@meta-gen.com)
*/
class Callback__Exception
{
	private $AddrEmail;
	private $AddrEmailAdmin;
	private $AddrEmailDev;

	private $delim;

	private $Message;
	private $Fichier;
	private $Line;
	private $Code;

	/**
	 * Constructeur de class
	 */
	public function __construct()
	{
		$this->delim = md5(uniqid(mt_rand()));

	$Addr_admin = array('info@cyberiade.ch');
		$Addr_dev = array('info@cyberiade.ch');

		$this->AddrEmailAdmin = implode(', ', $Addr_admin);
		$this->AddrEmailDev = implode(', ', $Addr_dev);
		$this->AddrEmail = implode(', ', array_merge($Addr_admin, $Addr_dev));

		$this->set_header_email();
	}

	/**
	 * CALLBACK de déclanchement d'une Exception sur un objet ou manuellement
	 */
	public function set_exception($e)
	{
		if (strstr($e->getMessage(), '[--]') === false)
		{
			$this->Message = $e->getMessage();
			$msgUser = false;
		}
		else
		{
			$errSTRtmp = explode('[--]', $e->getMessage());
			$this->Message = $errSTRtmp[1];
			$msgUser = $errSTRtmp[0];
		}

		$this->Fichier = $e->getFile();
		$this->Line = $e->getLine();
		$this->Code = $e->getCode();

		$msg = $this->set_simple_error();

		$pre_body = "Message au format MIME.\r\n";
		$pre_body .= "--{$this->delim}\r\n";
		$pre_body .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
		$pre_body .= "Content-Transfer-Encoding:8bit\r\n";
		$pre_body .= "\n";
		$pre_body .= "$msg\r\n";
		$pre_body .= "\r\n";
		$pre_body .= "--{$this->delim}--\n";

		mail($this->AddrEmail, 'Alert BUG', $pre_body , $this->headers);
	}

	private function set_header_email()
	{
		$this->headers .= "MIME-Version: 1.0\r\n";
		$this->headers .= "From: {$_SERVER['SERVER_NAME']} <mailGen@{$_SERVER['SERVER_NAME']}>\r\n";
		$this->headers .= "Reply-To: NoReply <noreply@{$_SERVER['SERVER_NAME']}>\r\n";
		$this->headers .= "Content-Type:multipart/related; boundary=\"{$this->delim}\"\r\n";
		$this->Headers .= "\n";
	}

	private function set_simple_error()
	{
		return '
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
			<html>
				<head></head>
				<body>
					<table align="left" width="600" cellpadding="0" cellspacing="0">
						<tr>
							<td height="50" bgcolor="#CCCCCC" border="1">
								<font color="#FF0000">Erreur mailGen => '. $_SERVER['SERVER_NAME'] .' | ip: '. $_SERVER['SERVER_ADDR'] .'</font>
							</td>
						</tr>
						<tr>
							<td>
								<br><br>
								<table border="1">
									<tr>
										<td>Type : </td>
										<td>Déclanchement par Exception, code: '. $this->Code .'</td>
									</tr>
									<tr>
										<td>Message : </td>
										<td><font color="#FF0000">'. $this->Message .'</font></td>
									</tr>
									<tr>
										<td>Fichier : </td>
										<td>'. $this->Fichier .'</td>
									</tr>
									<tr>
										<td>Ligne : </td>
										<td>'. $this->Line .'</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</body>
			</html>
		';
	}
}

?>