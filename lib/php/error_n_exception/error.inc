<?php

/**
 * Class de gestions des erreur déclancher par la fonction php trigger_error
 *
 * Ci dessous la définitions des niveau d'erreur à respecter de la plus importante à la moins importante
 * E_USER_ERROR     :: Erreur provenant magoritairement d'erreur interne au script, variable ou methode non déclaré... on envoi un email au developpeur
 * E_WARNING   :: Erreur provenant d'un contrôle sur des variable génré par le script on envoi un email au admin
 * E_USER_NOTICE    :: Erreur venant de l'utilisateur du site, Mauvais Login.... AUCUN email au admin est envoyer
 *
 * @author Tomasina pierre - Meta-Gen.com  (sora@meta-gen.com)
*/
class Callback__TrgError
{
	static private $_instance;

	public $out;

	private $AddrEmail;
	private $AddrEmailAdmin;
	private $AddrEmailDev;

	private $Convert_ErInt_to_ErStr;

	private $ErrINT;
	private $ErrMSG;
	private $ErrFILE;
	private $ErrLINE;
	private $ErrCONTEXT;

	private $delim;

	/**
	 * Constructeur de class
	 */
	public function __construct(array $args = array())
	{
		$this->delim = md5(uniqid(mt_rand()));

		$Addr_admin = isset($args["addr_admin"]) ? $args["addr_admin"] : array();
		$Addr_dev = isset($args["addr_dev"]) ? $args["addr_dev"] : array('info@cyberiade.ch');

		$Addr_admin = array('debug@cyberiade.ch');
		$Addr_dev = array('debug@cyberiade.ch');

		$this->AddrEmailAdmin = implode(', ', $Addr_admin);
		$this->AddrEmailDev = implode(', ', $Addr_dev);
		$this->AddrEmail = implode(', ', array_merge($Addr_admin, $Addr_dev));

		$this->Convert_ErInt_to_ErStr = array(
			1 => 'E_ERROR',
			2 => 'E_WARNING',
			4 => 'E_PARSE',
			8 => 'E_NOTICE',
			16 => 'E_CORE_ERROR',
			32 => 'E_CORE_WARNING',
			64 => 'E_COMPILE_ERROR',
			128 => 'E_COMPILE_WARNING',
			256 => 'E_USER_ERROR',
			512 => 'E_USER_WARNING',
			1024 => 'E_USER_NOTICE',
			2048 => 'E_STRICT',
			4096 => 'E_RECOVERABLE_ERROR',
			8192 => 'E_DEPRECATED',
			16384 => 'E_USER_DEPRECATED',
			30719 => 'E_ALL',
		);

		$this->set_header_email();
	}

	/**
	 * Méthode d'instance static Sigleton pour garder la même instance de class à chaque appel
	 * @return objet l'instance de la class courrante
	 */
	public static function getInstance($args = array())
	{
		if (is_null(self::$_instance))
		{
			$c_name = __class__;
			self::$_instance = new $c_name($args);
		}
		return self::$_instance;
	}

	/**
	 * CALLBACK de trigger_error
	 *
	 * @param int, niveau d'erreur inscrit en numérique
	 * @param string, message d'erreur
	 * @param string, fichier contenant l'erreur
	 * @param string, ligne contenant l'erreur
	 * @param array, tableau contenant les variables déclaré au moment du crash
	 *
	 * @return Envoi un email au admin du site et appel show_msg_error_for_user()
	 */
	public function set_error($errINT, $errSTR, $errFILE, $errLINE, $errCONTEXT)
	{
		if (strstr($errSTR, '[--]') === false)
		{
			$this->ErrMSG = $errSTR;
			$msgUser = $errSTR;
		}
		else
		{
			$errSTRtmp = explode('[--]', $errSTR);
			$this->ErrMSG = $errSTRtmp[1];
			$msgUser = $errSTRtmp[0];
		}

		$this->ErrINT = $errINT;
		$this->ErrFILE = $errFILE;
		$this->ErrLINE = $errLINE;
		$this->ErrCONTEXT = $errCONTEXT;

		switch ($this->ErrINT)
		{
			case 256:
				/// E_USER_ERROR
				/// TODO Envoi email DEV
				$msg = $this->set_backtrace();
				$this->send_email($this->AddrEmailDev, $msg);
			break;

			case 512:
				/// E_USER_WARNING
				/// TODO Envoi email DEV + ADMIN
				$msg = $this->set_simple_error();
				$this->send_email($this->AddrEmail, $msg);
			break;

			/*case 1024:
				/// E_USER_NOTICE
				/// TODO juste envoi msg user
				$this->show_msg_error_for_user($msgUser);
			break;*/

			default :
				/// TODO juste envoi email au dev
				$msg = $this->set_backtrace();
				$this->send_email($this->AddrEmailDev, $msg);
			break;
		}
	}

	/**
	 * DOIT retourner un message d'erreur soigné à l'utilisateur en bonne éduforme sans indications du système interne
	 * @return string html, json, dom, dépend du type système d'appel des page du site
	*/
	private function show_msg_error_for_user($msg)
	{
		switch ($this->out)
		{
			case 'json':
				echo json_encode(array('success' => false, 'msg' => trim($msg)));
				exit;
			break;

			case 'xml':
				exit;
			break;

			case 'text':
				echo "";
				exit;
			break;

			case 'data':
			break;
		}
	}

	/**
	 * Envoi l'email d'erreur
	 */
	private function send_email($addr, $msg)
	{
		$pre_body = "Message au format MIME.\r\n";
		$pre_body .= "--{$this->delim}\r\n";
		$pre_body .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
		$pre_body .= "Content-Transfer-Encoding:8bit\r\n";
		$pre_body .= "\n";
		$pre_body .= "$msg\r\n";
		$pre_body .= "\r\n";
		$pre_body .= "--{$this->delim}--\n";

		$rm = mail($addr, 'Alert BUG', $pre_body , $this->headers);
	}

	private function set_header_email()
	{
		if (empty($_SERVER['SERVER_NAME']))
			$_SERVER = array('SERVER_NAME' => 'elinca.savebox.ch', 'SERVER_ADDR' => '87.98.135.167');
		$this->headers .= "MIME-Version: 1.0\r\n";
		$this->headers .= "From: {$_SERVER['SERVER_NAME']} <mailGen@{$_SERVER['SERVER_NAME']}>\r\n";
		$this->headers .= "Reply-To: NoReply <noreply@{$_SERVER['SERVER_NAME']}>\r\n";
		$this->headers .= "Content-Type:multipart/related; boundary=\"{$this->delim}\"\r\n";
		$this->headers .= "\n";
	}

	private function set_simple_error()
	{
		if (empty($_SERVER['SERVER_NAME']))
			$_SERVER = array('SERVER_NAME' => 'elinca.savebox.ch', 'SERVER_ADDR' => '87.98.135.167');
		return '
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
			<html>
				<head></head>
				<body>
					<table align="left" width="600" cellpadding="0" cellspacing="0">
						<tr>
							<td height="50" bgcolor="#CCCCCC" border="1">
								<font color="#FF0000">Erreur mailGen => '. $_SERVER['SERVER_NAME'] .' | ip serveur: '. $_SERVER['SERVER_ADDR'] .'</font>
							</td>
						</tr>
						<tr>
							<td>
								<br><br>
								<table border="1">
									<tr>
										<td>Type : </td>
										<td>'. $this->Convert_ErInt_to_ErStr[$this->ErrINT] .'</td>
									</tr>
									<tr>
										<td>Message : </td>
										<td><font color="#FF0000">'. $this->ErrMSG .'</font></td>
									</tr>
									<tr>
										<td>Fichier : </td>
										<td>'. $this->ErrFILE .'</td>
									</tr>
									<tr>
										<td>Ligne : </td>
										<td>'. $this->ErrLINE .'</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</body>
			</html>
		';
	}

	private function set_backtrace()
	{
		if (empty($_SERVER['SERVER_NAME']))
			$_SERVER = array('SERVER_NAME' => 'elinca.savebox.ch', 'SERVER_ADDR' => '87.98.135.167');
		return '
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
			<html>
				<head></head>
				<body>
					<table align="left" width="600" cellpadding="0" cellspacing="0">
						<tr>
							<td height="50" bgcolor="#CCCCCC" border="1">
								<font color="#FF0000">Erreur mailGen => '. $_SERVER['SERVER_NAME'] .' | Ip serveur : '. $_SERVER['SERVER_ADDR'] .'</font>
							</td>
						</tr>
						<tr>
							<td>
								<br><br>
								<table border="1">
									<tr>
										<td>Type : </td>
										<td>'. $this->Convert_ErInt_to_ErStr[$this->ErrINT] .'</td>
									</tr>
									<tr>
										<td>Message : </td>
										<td><font color="#FF0000">'. $this->ErrMSG .'</font></td>
									</tr>
									<tr>
										<td>Fichier : </td>
										<td>'. $this->ErrFILE .'</td>
									</tr>
									<tr>
										<td>Ligne : </td>
										<td>'. $this->ErrLINE .'</td>
									</tr>
								</table>
								<br><br>
							</td>
						</tr>
						<tr>
							<td>
								<table border="1">
									<tr>
										<td>BackTrace</td>
										<td><pre>'. print_r($this->ErrCONTEXT, true) .'</pre></td>
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