<?php
/**
 * Engine PHP Application Framework
 * http://seelaz.com.br
 *
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
 * @subpackage db
 * File: EntityValidator.php
 **/

import('engine.validators.*');

/** Validador dos dados da entidade
 * @author Silas R. N. Junior
 */
class EntityValidator {

	/** Mensagens da ultima validacao feita
	 * @var array
	 */
	private static $messages;

	/** Verifica a validade dos dados da entidade
	 * @param object $entity    Entidade a ser validada
	 * @return boolean
	 */
	public static function isValid($entity) {
		
		self::$messages = array();

		$rf = EntityManager::getReflectionData(get_class($entity));
		
		$prop = $rf->getORMProperties();
		while ($rf->getParentClass()) {
			$rf = $rf->getParentORMClass();
			$prop = array_merge($prop,$rf->getORMProperties());
		}

		foreach ($prop as $anotProp) {
				
			//$anotProp = new ReflectionORMProperty($class, $name);
			$value = $anotProp->getValue($entity);

			if ($anotProp->hasAnnotation("Future")) {
				if (!FutureValidator::isValid($value)) self::$messages[] = $anotProp->getName()."|".FutureValidator::message();
			}
			if ($anotProp->hasAnnotation("Length")) {
				LengthValidator::setMin($anotProp->getAnnotation("Length")->getMin());
				LengthValidator::setMax($anotProp->getAnnotation("Length")->getMax());
				if (!LengthValidator::isValid($value)) self::$messages[] = $anotProp->getName()."|".LengthValidator::message();
			}
			if ($anotProp->hasAnnotation("Max")) {
				MaxValidator::setValue($anotProp->getAnnotation("Max")->getValue());
				if (!MaxValidator::isValid($value)) self::$messages[] = $anotProp->getName()."|".MaxValidator::message();
			}
			if ($anotProp->hasAnnotation("Min")) {
				MinValidator::setValue($anotProp->getAnnotation("Min")->getValue());
				if (!MinValidator::isValid($value)) self::$messages[] = $anotProp->getName()."|".MinValidator::message();
			}
			if ($anotProp->hasAnnotation("NotEmpty")) {
				if (!NotEmptyValidator::isValid($value)) self::$messages[] = $anotProp->getName()."|".NotEmptyValidator::message();
			}
			if ($anotProp->hasAnnotation("NotNull")) {
				if (!NotNullValidator::isValid($value)) self::$messages[] = $anotProp->getName()."|".NotNullValidator::message();
			}
			if ($anotProp->hasAnnotation("Past")) {
				if (!PastValidator::isValid($value)) self::$messages[] = $anotProp->getName()."|".PastValidator::message();
			}
			if ($anotProp->hasAnnotation("Range")) {
				RangeValidator::setMax($anotProp->getAnnotation("Range")->getMax());
				RangeValidator::setMin($anotProp->getAnnotation("Range")->getMin());
				if (!RangeValidator::isValid($value)) self::$messages[] = $anotProp->getName()."|".RangeValidator::message();
			}
			if ($anotProp->hasAnnotation("Size")) {
				if (!SizeValidator::isValid($value)) self::$messages[] = $anotProp->getName()."|".SizeValidator::message();
			}
		}
		if (sizeof(self::$messages) > 0) return false; else return true;
	}

	/** Retorna as mensagens de erro encontradas na validacao
	 * @return array
	 */
	public static function getMessages() {
		return self::$messages;
	}
}

?>
