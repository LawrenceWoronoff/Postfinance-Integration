<?php

class Character
{
	/**
	 * Constructeur de class
	 */
	public function __construct () {
	}

	/**
	 * Permet de dététecter si une chaine de caratères est encodée en utf-8
	 * @param string la chaine de caratères à analyser
	 * @return bool retourne true si encodée en utf-8, ou false sinon.
	 * @access protected
	 */
	protected function is_utf8($string)
	{
		return (preg_match('%^(?:
			[\x09\x0A\x0D\x20-\x7E]
			| [\xC2-\xDF][\x80-\xBF]
			|\xE0[\xA0-\xBF][\x80-\xBF]
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
			|\xED[\x80-\x9F][\x80-\xBF]
			|\xF0[\x90-\xBF][\x80-\xBF]{2}
			| [\xF1-\xF3][\x80-\xBF]{3}
			|\xF4[\x80-\x8F][\x80-\xBF]{2}
		)*$%xs', $string) === 1);
	}

	/**
	 * Permet de dététecter si une chaine de caratères est encodée en cp1252
	 * @param string la chaine de caratères à analyser
	 * @return bool retourne true si encodée en cp1252, ou false sinon.
	 * @access protected
	 */
	protected function is_cp1252($string)
	{
		return (preg_match('/^(?:\x80|\x9F)*$/xs', $string) === 1);
	}

	/**
	 * Permet de dététecter si une chaine de caratères est encodée en iso-8859-15
	 * @param string la chaine de caratères à analyser
	 * @return bool retourne true si encodée en iso-8859-15, ou false sinon.
	 * @access protected
	 */
	protected function is_iso8859_15($string)
	{
		return (preg_match('%^(?:\xA4|\xBC|\xBD)*$%xs', $string) === 1);
	}

	/**
	 * Nettoie la chaîne de caractère en gardant uniquement les caractères imprimable.
	 * @param string la chaine de caratères à nettoyer
	 * @param charset OUT encodage sortie
	 * @param charset IN encodage entrée
	 * @param string chaîne nettoyer
	 */
	public function clean_string($str, $out, $in = false)
	{
		$r = $str;
		$in = $in == false ? $this->detect_encoding($str) : $in;

		$r = preg_replace('%([\x00-\x1F])%s', "", $r);
		if ($in == "iso-8859-15" || $in == "iso-8859-1")
			$r = preg_replace('%([\x7F-\x9F])%s', "", $r);
		else if ($in == "cp1252")
			$r = preg_replace('%(\x7F|\x81|\x8D|\x8F|\x90|\x9D)%s', "", $r);
		elseif ($in == "utf-8")
			$r = preg_replace('%(\xC2[\x80-\x9F])%s', "", $r);

		return iconv($in, $out, $r);
	}

	/**
	 * Detecte le format d'un chaîne
	 * @param string la chaîne à detecté
	 * @return string format de la chaîne detecter
	 */
	public function detect_encoding($str)
	{
		$ret = 'iso-8859-1';
		if ($this->is_utf8($str))
			$ret = 'utf-8';
		elseif ($this->is_cp1252($str))
			$ret = 'cp1252';
		elseif ($this->is_iso8859_15($str))
			$ret = 'iso-8859-15';
		return $ret;
	}
}

?>
