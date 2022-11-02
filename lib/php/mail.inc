<?php

class email
{
	/**
	 * @var string header mail
	 */
	protected $headers;

	/**
	 * @var string delimiter MIME boundary
	 */
	protected $delim;

	/**
	 * Constructeur de class
	 */
	public function __construct()
	{
	}

	private function set_delimiter()
	{
		$this->delim = md5(uniqid(mt_rand()));
	}

	protected function set_header(array $args)
	{
		$reply = !empty($args['reply']) ? "<{$args['reply']}>" : "NoReply <noreply@{$_SERVER['SERVER_NAME']}>";
		$contentType = !empty($args['contentType']) ? $args['contentType'] : "multipart/related";

		$this->headers = "MIME-Version: 1.0\r\n";
		$this->headers .= "From: {$_SERVER['SERVER_NAME']} <emailGen@{$_SERVER['SERVER_NAME']}>\r\n";
		$this->headers .= "Reply-To: $reply\r\n";
		$this->headers .= "Content-Type:$contentType; boundary=\"{$this->delim}\"\r\n\n";
	}

	public function simpleText(array $args)
	{
		$this->set_delimiter();
		if (!isset($args['head']))
			$args['head'] = array();
		$this->set_header($args['head']);

		$pre_body = "Message au format MIME.\r\n";
		$pre_body .= "--{$this->delim}\r\n";
		$pre_body .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
		$pre_body .= "Content-Transfer-Encoding:8bit\r\n";
		$pre_body .= "\n";
		$pre_body .= "{$args['msg']}\r\n";
		$pre_body .= "\r\n";
		$pre_body .= "--{$this->delim}--\n";
		$sm = mail($args['addr'], $args['objet'], $pre_body , $this->headers);
		return $sm;
	}

	/**
	 * envoi un mail avec des pièces jointes
	 * @param array contenant les donnée pour les entêtes
	 * @param array contenant les données pour le corps
	 * @param array list des url RÉEL des fichiers à joindres
	 */
	public function mail_item(array $head, array $body, array $items)
	{
		$r = false;
		if (!empty($body['addr']) && !empty($body['objet']))
		{
			$MSG = !empty($body['msg']) ? $body['msg'] : "";
			$this->set_delimiter();
			if (empty($head["contentType"]))
				$head["contentType"] = "multipart/mixed";
			$this->set_header($head);

			$pre_body = "Message au format MIME.\r\n";
			$pre_body .= "--{$this->delim}\r\n";
			$pre_body .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
			$pre_body .= "Content-Transfer-Encoding:8bit\r\n";
			$pre_body .= "\n";
			$pre_body .= "$MSG\r\n";
			$pre_body .= "\r\n";

			foreach ($items AS $v)
			{
				if (file_exists($v))
				{
					$filect = false;
					//$finfo = finfo_open(FILEINFO_MIME); // MARCHE PAS AVEC L'HEBERGEMENT tizoo
					$finfo = false;
					if (!$finfo)
					{
						$pi = pathinfo($v);
						switch ($pi["extension"])
						{
							case "pdf":
								$filect = "application/pdf ";
							break;
						}
					}
					else
						$filect = finfo_file($finfo, $v);

					if ($filect)
					{
						$contentFile = file_get_contents($v);
						$dataB64 = chunk_split(base64_encode($contentFile));

						$pre_body .= "--{$this->delim}\n";
						$pre_body .= "Content-Type: $filect; name=\"".basename($v)."\"\r\n";
						$pre_body .= "Content-Transfer-Encoding: base64\r\n";
						$pre_body .= "Content-Disposition: attachment; filename=\"".basename($v)."\"\r\n\n";
						$pre_body .= "$dataB64\r\n";
					}
				}
			}
			$pre_body .= "--{$this->delim}--\n";
			$r = mail($body['addr'], $body['objet'], $pre_body , $this->headers);
		}
		return $r;
	}
}

?>
