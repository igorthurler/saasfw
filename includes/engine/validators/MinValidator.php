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
 * File: MinValidator.php
 **/

import('engine.validators.IValidator');

/** Verifica se um valor e menor ou igual seu minimo
 * @author Silas R. N. Junior
 */
class MinValidator implements IValidator {

	/**
	 * @var int
	 */
	private static $value;

	/**
	 * @return int
	 */
	public static function getValue() {
		return self::$value;
	}

	/**
	 * @param int $newValue
	 * @return void
	 */
	public static function setValue($newValue) {
		self::$value = $newValue;
	}

	/** Verifica se o valor é valido
	 * @param mixed $value   Valor
	 * @return boolean
	 */
	public static function isValid($value) {
		return $value > self::$getValue() ? true : false;
	}

	/** Retorna a mensagem de erro
	 * @return string
	 */
	public static function message() {
		return "O valor minimo permitido e de ate ".self::$getValue();
	}
}

?>