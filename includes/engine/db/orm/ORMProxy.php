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
 * @subpackage core
 * File: ORMProxy.php
 **/


/** Pattern Proxy para fins genéricos
 * @author Silas R. N. Junior
 */
class ORMProxy {

	/** Objeto encapsulado
	 * @var object
	 */
	private $object;

	/** Classe de reflexao do objeto
	 * @var ReflectionORMClass
	 */
	private $class;

	/** Flag de inicializacao de objetos compostos
	 * @var boolean
	 */
	private $initialize;

	/**
	 * @param mixed $entity   Objeto ou Classe do proxy
	 * @param boolean $initialize   Flag de inicializacao de objetos compostos [default: false]
	 */
	public function ORMProxy(&$entity, $initialize = false) {
		$this->initialize = $initialize;
		if (is_object($entity)) {
			if ($entity instanceof ReflectionClass ) {
				$this->object = $entity->newInstance();
				$this->class = EntityManager::getReflectionData($entity->getName());
			} else {
				$this->object = $entity;
				$this->class = EntityManager::getReflectionData(get_class($entity));
			}
		} else if (is_string($entity)) {
			if (class_exists($entity)) {
				$this->object = new $entity();
				$this->class = EntityManager::getReflectionData($entity);
			} else {
				throw new Exception("Erro criando proxy para classe ".$entity.". Classe Inexistente ou nao incluida.");
			}
		} else if (!isset($entity)) {
			throw new Exception("Erro criando proxy. Classe não informada.");
		}
	}

	/**
	 */
	public function &__get($property) {
		$null = null;
		$path = explode(".",$property);
		$splicePath = $path;
		$currentProperty = (count($path) > 1 ? join("",array_splice($splicePath,0,1)) : $property);
		$rf = $this->class;
		if ($rf->hasORMProperty($currentProperty)) {
			$currentReflectionProperty = $rf->getORMProperty($currentProperty);
		} else {
			throw new Exception("A classe ".$this->class." nao possui a propriedade ".$currentProperty);
		}
		/*
		 * [HACK] hasProperty nao funciona da forma desejada.
		/
		$props = $rf->getORMProperties();
		$has = false;
		foreach ($props as $p) {
			if ($p->name == $currentProperty) {
				$has = true;
				$currentReflectionProperty = $p;
				break;
			}
		}
		if (!$has) {
			while ($rf->getParentClass()) {
				$rf = $rf->getParentORMClass();
				$props = $rf->getORMProperties();
				foreach ($props as $p) {
					if ($p->name == $currentProperty) {
						$currentReflectionProperty = $p;
						$has = true;
						break 2;
					}
				}
			}
			if (!$has) {
				throw new Exception("A classe ".$this->class." nao possui a propriedade ".$currentProperty);
			}
		}
		/*
		 * [HACK] Fim do hack
		*/
		
		if (count($path) > 1) {
			if ($currentReflectionProperty->getValue($this->object) != null) {
				$v = $currentReflectionProperty->getValue($this->object);
				$object = new ORMProxy($v,$this->initialize);
			} else if ($this->initialize) {
				$object = new ORMProxy($this->_getInitializedObject_($currentReflectionProperty),$this->initialize);
				$currentReflectionProperty->setValue($this->object,$object->getSubject());
			}
			if (isset($object)) {
				$splicePath = $path;
				$nextPath = array_splice($splicePath,1);
				$p = implode(".",$nextPath);
				$ret = $object->{$p};
				return $ret;
			} else {
				return $null;
			}
		} else {
			$value = $currentReflectionProperty->getValue($this->object);

			if (is_null($value)) {
					
				if ($currentReflectionProperty->hasAnnotation('var')) {
					$var = trim($currentReflectionProperty->getAnnotation('var'));
				} else {
					$var = "string";
				}
				if (!class_exists($var) || in_array($var,Annotations::$ignore) || in_array($var,array("string","int","integer","boolean","float","date","time","money"))) {
					return $value;
				} else {
					if ($this->initialize) {
						$currentReflectionProperty = $this->_getInitializedObject_($currentReflectionProperty);
						return $currentReflectionProperty;
					} else {
						return $value;
					}
				}
			} else {
				return $value;
			}
		}
	}

	/**
	 */
	public function __set($property,$value) {
		$path = explode(".",$property);
		if (count($path) > 1) {
			$prop = array_splice($path,count($path) - 1,1);
			$object = new ORMProxy($this->{implode(".",$path)});
			$object->{$prop[0]} = $value;
		} else {
			if (is_callable(array($this->object,EntityUtils::getSetter($property)))) {
				$this->object->{EntityUtils::getSetter($property)}($value);
			} else {
				throw new ReflectionException("A classe ".get_class($this->object)." nao possui o metodo Setter chamado [".$property."]");
			}
		}
	}

	/**
	 */
	public function __call($name, $arguments) {
		if (is_callable(array($this->object,$name))) {
			if ($arguments) {
				return call_user_func_array(array($this->object,$name),$arguments);
			} else {
				return call_user_func(array($this->object,$name));
			}
		}
	}

	/**
	 */
	public function __destruct() {
		$this->class = null;
		$this->object = null;
	}

	private function _getInitializedObject_(ReflectionORMProperty $property) {
		
		if ($property->getType() == "Collection") {
			$rf = EntityManager::getReflectionData($property->getTargetEntity());
		} else {
			$rf = EntityManager::getReflectionData($property->getType());
		}
		
		$ob = $rf->newInstance();

		if ($property->isCollection()) {
			return;
		}
		$bDir = false;
		//Verifica se o mapeamento e bidirecional
		if ($property->getMappedBy()) {
			$bDir =  $property->getMappedBy();
		} else if ($rf->isMapping($this->class->getName(),$property->getName())) {
			$bDir =  $rf->getMapped($this->class->getName(),$property->getName());
		}

		if ($bDir) {
			$mappedProperty = $rf->getORMProperty($bDir);
			if ($mappedProperty->getType() != "Collection") {
				$mappedProperty->setValue($ob,$this->object);
			} else {
				//Nao tratado ainda
			}
		}
		$property->setValue($this->object,$ob);
		return $ob;
	}

	public function getSubject() {
		return $this->object;
	}
}
?>