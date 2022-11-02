<?php

/**
 * CLASS STATIC UNIQUEMENT
 * Class de contrôle d'élement
 * @author TOMASINA PIERRE - Meta-Gen (sora@meta-gen.com)
 */
class ParamCheck
{
	static public function inputCheck(array $args)
	{
		$vals = array('success' => true, 'values' => array());
		foreach ($args AS $arg)
		{
			$v = "§nullFlase§";
			$LIMIT = isset($arg['limit']) ? $arg['limit'] : array();
			$valeur = isset($_REQUEST[$arg['val']]) ? rawurldecode($_REQUEST[$arg['val']]) : false;
			if ($valeur === false)
			{
				if (isset($arg['bond']))
					$vals['success'] = false;
				continue;
			}
			switch ($arg['type'])
			{
				case 'id' :
					$v = self::is_id($valeur);
					if ($v === false)
					{
						$vals['success'] = false;
						$v = $valeur;
					}
				break;

				case 'int' :
					$v = self::isint($valeur);
					if ($v === false)
					{
						$vals['success'] = false;
						$v = $valeur;
					}
				break;

				case 'bool' :
					$v = self::is_boolean($valeur);
					if ($v === null)
					{
						$vals['success'] = false;
						$v = $valeur;
					}
				break;

				case 'login' :
					$v = self::is_login($valeur);
					if ($v === false)
					{
						$vals['success'] = false;
						$v = $valeur;
					}
				break;

				case 'passwd' :
					$v = self::is_passwd($valeur);
					if ($v === false)
					{
						$vals['success'] = false;
						$v = $valeur;
					}
				break;

				case 'text' :
					if (!is_string($valeur) || strlen($valeur) === 0)
						$vals['success'] = false;
					$v = $valeur;
				break;

				case 'string' :
					$t = isset($arg['param']['type']) ? $arg['param']['type'] : null;
					if (!is_string($valeur))
						$vals['success'] = false;
					else if ($t == "arrayId" && isset($arg['param']['delimiter']))
					{
						$ids_tmp = explode($arg['param']['delimiter'], $valeur);
						if (count($ids_tmp) == 1 && trim($ids_tmp[0]) == "")
							$ids_tmp = array();
						$check = true;
						foreach ($ids_tmp AS &$id_tmp)
						{
							$vCheck = self::is_id($id_tmp);
							if ($vCheck === false)
							{
								$check = false;
								break;
							}
							else
								$id_tmp = $vCheck;
						}
						$v = $ids_tmp;
					}
					else
						$v = $valeur;
				break;

				case 'date' :
					$t = isset($arg['param']['type']) ? $arg['param']['type'] : null;
					$r = isset($arg['param']['retour']) ? $arg['param']['retour'] : null;
					if ($valeur == "")
						$v = isset($arg['param']['retourNull']) ? $arg['param']['retourNull'] : 0;
					else
					{
						$v = self::is_date($valeur, $t, $r);
						if ($v === false)
						{
							$vals['success'] = false;
							$v = $valeur;
						}
					}
				break;

				case 'keyword' :
					$v = '';
					if ($valeur !== '')
					{
						$v = self::is_keyword($valeur);
						if ($v === false)
						{
							$vals['success'] = false;
							$v = $valeur;
						}
					}
				break;

				case 'html' :
					$v = '';
					if ($valeur !== '')
					{
						$v = self::htmlCleaner($valeur);
						if ($v === false)
						{
							$vals['success'] = false;
							$v = $valeur;
						}
					}
				break;
			}
			if ($v !== "§nullFlase§")
			{
				if (is_string($v) OR is_int($v) OR is_array($v))
					if (self::check_limit($v, $LIMIT) === false && $vals['success'] === true)
						$vals['success'] = false;
				$vals['values'][$arg['val']] = $v;
			}
			else
				trigger_error("Le type « {$arg['type']} » n'existe pas", E_USER_ERROR);
		}
		return $vals;
	}

	/**
	 * Contrôle que $v est un entier différent de 0
	 * @param mixed valeur à contrôler
	 * @return INT nettoyer(si int dans une chaîne) | Boolean False
	 */
	static public function isint($v)
	{
		if (preg_match('/^[0-9]*$/', $v) === 1)
			return intval($v);
		else
			return false;
	}

	/**
	 * Contrôle que $v est un entier différent de 0
	 * @param mixed valeur à contrôler
	 * @return INT nettoyer(si int dans une chaîne) | Boolean False
	 */
	static public function is_id($v)
	{
		if (preg_match('/^[1-9][0-9]*$/', $v) === 1)
			return ($v = intval($v)) === 0 ? false : $v;
		else
			return false;
	}

	/**
	 * Contrôle que $v est un boolean
	 * @param mixed valeur à contrôler
	 * @return Boolean | NULL (POUR PAS BOOLEAN)
	 */
	static public function is_boolean($v)
	{
		$ret = null;
		if (is_bool($v))
			$ret = $v;
		else
		{
			if ($v === 1)
				$ret = true;
			elseif ($v === 0)
				$ret = false;
			elseif ($v === 'true')
				$ret = true;
			elseif ($v === 'false')
				$ret = false;
			elseif ($v === 'on')
				$ret = true;
			elseif ($v === 'off')
				$ret = false;
		}
		return $ret;
	}

	/**
	 * Contrôle que $v est une chaîne de caractère
	 * @param mixed valeur à contrôler
	 * @return String nettoyer | Boolean False
	 */
	static public function is_stringer($v)
	{
		if (is_string($v))
			return $v;
		else
			return false;
	}

	/**
	 * Contrôle que $v est un mot de passe valide
	 * @param mixed valeur à contrôler
	 * @return valeur | Boolean False
	 */
	static public function is_passwd($v)
	{
		if (preg_match('/^[a-z0-9\-%_#~;\?\.\:\$]{6,64}$/i', $v) === 1)
			return $v;
		else
			return false;
	}

	/**
	 * Contrôle que $v est un mot login valide
	 * @param mixed valeur à contrôler
	 * @return valeur | Boolean False
	 */
	static public function is_login($v)
	{
		if (preg_match('/([0-9a-z_-]){1,32}/i', $v) === 1)
			return $v;
		else
			return false;
	}

	/**
	 * Contrôle que $v est une chaîne de caractère composer de mot séparé par un point virgule (foo;bar;baz)
	 * @param mixed valeur à contrôler
	 * @return valeur | Boolean False
	 */
	static public function is_keyword($v)
	{
		if (preg_match('/^\w*(;\w+)*$/i', $v) === 1)
			return $v;
		else
			return false;
	}

	/**
	 * Contrôle que $v est une date valide
	 * @param mixed valeur à contrôler
	 * @param string format date
	 * @return valeur | Boolean False
	 */
	static public function is_date($v, $f, $r = null)
	{
		$ret = false;
		$REG_TYPE = array(
			'd' => '(([0-2][0-9]?)|(3[0-1]))',
			'm' => '((0?[1-9])|(1[0-2]))',
			'Y' => '([0-9]{4})',
			'H' => '(([0-1][0-9])|(2[0-4]))',
			'i' => '([0-5][0-9])'
		);
		switch ($f)
		{
			case 'dmY' :
				if (preg_match("/^{$REG_TYPE['d']}\/{$REG_TYPE['m']}\/{$REG_TYPE['Y']}/", $v) === 1)
				{
					$ret = true;
					$date = explode("/", $v);
					if ($r !== null)
					{
						if ($r == "U")
							$ret = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
					}
					else
						$ret = $v;
				}
			break;

			case 'Hi' :
				if (preg_match("/^{$REG_TYPE['H']}:{$REG_TYPE['i']}/", $v) === 1)
				{
					$ret = true;
					$date = explode(":", $v);
					$today = explode('/', date("d/m/Y"));
					if ($r !== null)
					{
						if ($r == "U")
							$ret = mktime($date[0], $date[1], 0, $today[1], $today[0], $today[2]);
					}
					else
						$ret = $v;
				}
			break;

			case 'dmY Hi' :
				if (preg_match("/^{$REG_TYPE['d']}\/{$REG_TYPE['m']}\/{$REG_TYPE['Y']}\s{$REG_TYPE['H']}:{$REG_TYPE['i']}/", $v) === 1)
				{
					$ret = true;
					$date = array_merge(
						explode("/", substr($v, 0, stripos($v, ' '))),
						explode(":", substr($v, stripos($v, ' ')))
					);
					if ($r !== null)
					{
						if ($r == "U")
							$ret = mktime($date[3], $date[4], 0, $date[1], $date[0], $date[2]);
					}
					else
						$ret = $v;
				}
			break;
		}
		return $ret;
	}

	/**
	 * Check si les adresse sont valide
	 * @param string | @param array , Soit une adresse email soit un tableau d'email
	 * @return Boolean true si email valide ou false sinon
	 */
	static public function check_email($addr)
	{
		$r = true;
		if (is_array($addr))
		{
			foreach ($addr AS $val)
			{
				if (!preg_match('/^[a-z]+[a-z0-9_\-\.]+@[a-z0-9\.\-]+\.[a-z]{2,16}$/i', $val))
				{
					$r = false;
					break;
				}
				else
				{
					list($compte, $domain) = explode('@', $val, 2);
					if (!checkdnsrr($domain, 'MX') || !checkdnsrr($domain, 'A'))
					{
						$r = false;
						break;
					}
				}
			}
		}
		else
		{
			if (!preg_match('/^[a-z]+[a-z0-9_\-\.]+@[a-z0-9\.\-]+\.[a-z]{2,16}$/i', $addr))
				$r = false;
			else
			{
				list($compte, $domain) = explode('@', $addr, 2);
				if (!checkdnsrr($domain, 'MX') || !checkdnsrr($domain, 'A'))
					$r = false;
			}
		}
		return $r;
	}

	/**
	 * Contole que la valeur donné est un tableau remplis d'id
	 * @param mixed value
	 * @return value clean || Boolean FALSE
	 */
	static public function is_listid($v)
	{
		$r = false;
		$vals = array();
		if (is_array($v))
		{
			$r = true;
			foreach ($v AS $value)
			{
				if (($vclean = self::is_id($value)) !== false)
					$vals[] = $vclean;
				else {
					$r = false;
					break;
				}
			}
			if ($r)
				$r = $vals;
		}
		return $r;
	}

	/**
	 * Nettoie le HTML. Transforme les balises en entité
	 * @param mixed value
	 */
	static public function htmlCleaner($v)
	{
		$ret = false;
		if (is_string($v))
		{
			//$ret = htmlspecialchars($v, ENT_QUOTES, 'UTF-8', true);
			$ret = $v;
		}
		return $ret;
	}

	/**
	 * Contrôle l'intervalle d'une valeur
	 * @type protected
	 * @param string | @param int | @param array
	 * @param int taille minimal
	 * @param int taille maximal
	 * @return Boolean True si réussi ou false sinon
	 */
	static public function check_limit($val, array $limit)
	{
		$ret = false;
		$min = isset($limit['min']) ? (is_int($limit['min']) ? $limit['min'] : 0 ) : 0;
		$max = isset($limit['max']) ? (is_int($limit['max']) ? $limit['max'] : NULL ) : NULL;
		if (is_string($val))
		{
			$ret = strlen($val) >= $min ? true : false;
			if ($max !== NULL && $ret)
				$ret = strlen($val) <= $max ? true : false;
		}
		elseif (is_int($val))
		{
			$ret = $val >= $min ? true : false;
			if ($max !== NULL && $ret)
				$ret = $val <= $max ? true : false;
		}
		elseif (is_array($val))
		{
			$ret = count($val) >= $min ? true : false;
			if ($max !== NULL && $ret)
				$ret = count($val) <= $max ? true : false;
		}
		return $ret;
	}
}

?>
