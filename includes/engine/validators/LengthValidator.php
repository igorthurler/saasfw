<?php
/**
 * Engine PHP Application Framework
 * http://seelaz.com.br
 * Copyright (C) 2006-2012 Silas "Seelaz" Junior <seelaz@gmail.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @license http://www.fsf.org/licensing/licenses/gpl.html
 *
 * @filesource
 * @package engine
 * @subpackage validators
 * File: LengthValidator.php
 **/

import('engine.validators.IValidator');

/** Verifica se o tamanho da string confere
 * @author Silas R. N. Junior
 */
class LengthValidator implements IValidator {

	/**
	 * @var int
	 */
	private static $min = 0;

	/**
	 * @var int
	 */
	private static $max;

	/**
	 * @return int
	 */
	public static function getMin() {
		return self::$min;
	}

	/**
	 * @param int $newMin
	 * @return void
	 */
	public static function setMin($newMin) {
		self::$min = $newMin;
	}

	/**
	 * @return int
	 */
	public static function getMax() {
		return self::$max;
	}

	/**
	 * @param int $newMax
	 * @return void
	 */
	public static function setMax($newMax) {
		self::$max = $newMax;
	}

	/** Verifica se o valor é valido
	 * @param mixed $value   Valor
	 * @return boolean
	 */
	public static function isValid($value) {
		if (self::getMax()) {
			return ((strlen($value) >= self::getMin())&&(strlen($value) <= self::getMax())) ? true : false;
		} else {
			return (strlen($value) >= self::getMin()) ? true : false;
		}
	}

	/** Retorna a mensagem de erro
	 * @return string
	 */
	public static function message() {
		$msg1 = "";
		$msg2 = "";
		if (self::getMin() > 0) {
			$msg1 = " maior que ".self::getMin();
		}
		if (self::getMax() > 0) {
			$msg2 = " menor que ".self::getMax();
		}
		if (self::getMin() == self::getMax())
			return "O campo deve possuir uma quantidade de caracteres igual a ".self::getMin();
		else
			return "O campo deve possuir uma quantidade de caracteres".join(" e ",array($msg1,$msg2));
	}
}

?>