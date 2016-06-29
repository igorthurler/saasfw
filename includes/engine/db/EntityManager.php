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
 * @subpackage db
 * File: EntityManager.php
 **/

import('engine.core.EntityCacher');
import('engine.core.IEntityContainer');

/** Gerenciador de Entidades
 * @author Silas R. N. Junior
 */
class EntityManager {

	/** Dados das entidades
	 * @var array
	 */
	private static $data;

	/** Controle de cache de entidades
	 * @var array
	 */
	private static $cacher;

	/** Array de loaders pré gerados
	 * @var array
	 */
	private static $loaders;

	/** Array de persisters pré gerados
	 * @var array
	 */
	private static $persisters;

	/** Array de deleters pré gerados
	 * @var array
	 */
	private static $deleters;

	/**
	 * $var array
	 */
	private static $nodes;
	
	/** Proxies de entidades
	 * @var array
	 */
	private static $proxies = array();

	/** Retorna o objeto de reflexao da classe
	 * @param string $className   Nome da classe
	 * @return ReflectionORMClass
	 */
	public static function getReflectionData($className) {
		if (is_object($className)) $className = get_class($className);
		if (!isset(self::$data[$className])) {
			self::$data[$className] = new ReflectionORMClass($className);
		}
		return self::$data[$className];
	}

	/** Obtem a instancia do cache
	 * @return EntityCacher
	 */
	public static function getCacher() {
		if (!self::$cacher) {
			if (defined('ENGINE_SESSION_CACHE') && isset($_SESSION['ENGINE_CACHE'])) {
				self::$cacher = unserialize($_SESSION['ENGINE_CACHE']);
				self::$cacher->updateReferenceData();
			} else {
				self::$cacher = new EntityCacher();
			}
		}
		return self::$cacher;
	}

	/** Cria um lazy fetch proxy para a entidade
	 * @param object $object
	 * @return IEntityContainer
	 */
	public static function &proxyfy(&$object) {
		$className = get_class($object);
		
		//Se for um proxy retorna ele
		if(strstr($className,"LazyFetchProxy")) return $object;

		if (class_exists($className)  && in_array( $className, get_declared_classes() )) {
			$proxyClassName = "LazyFetchProxy".$className;
			
			if (!class_exists($proxyClassName)) {
				$rf = EntityManager::getReflectionData($className);
				$classDef = "class LazyFetchProxy".$className." extends ".$className." implements IEntityContainer {
	      		private \$subject;
	      		private \$initialised = false;
	
	      		public function LazyFetchProxy".$className."(&\$subject) {
	      			\$this->subject = \$subject;
	  			}
	  			
	  			public function init(&\$subject = null) {
	      			if (\$subject) {
	      				\$this->subject = \$subject;
	      				\$this->initialised = true;
	      				return true;
	      			}
	  				try {
		  				if (!\$this->initialised) {
	
		  					DAOFactory::getDAO()->load(\$this->subject);
		  				
			  				\$this->initialised = true;
			  			}
			  			return true;
			  		} catch (Exception \$e) {
			  			throw \$e;
			  		}
	  			}
	  			public function __destruct() {
	  				\$this->subject = null;
	  			}
	      				
	  			public function __call(\$method, \$param) {
	  				if (\$this->init()) {
		  				if (is_callable(array(\$this->subject,\$method))) {
		  					if (\$param) {
		  						return call_user_func_array(array(\$this->subject,\$method),\$param);
		  					} else {
		  						return call_user_func(array(\$this->subject,\$method));
		  					}
		  				}
		  			}
	  			}
	  			public function &getSubject() {
	  				return \$this->subject;
	  			}
	  			";
				foreach ($rf->getORMProperties() as $property) {
					if ($rf->hasMethod("get".ucwords($property->getName()))) {
						$classDef .= "public function get".ucwords($property->getName())."() {
			      			if (\$this->init()) {
			      				return \$this->subject->get".ucwords($property->getName())."();
			      			}
			      		}
			      		";
					}
					if ($rf->hasMethod("is".ucwords($property->getName()))) {
						$classDef .= "public function is".ucwords($property->getName())."() {
			      			if (\$this->init()) {
			      				return \$this->subject->is".ucwords($property->getName())."();
			      			}
			      		}
			      		";
					}
					if ($rf->hasMethod("set".ucwords($property->getName()))) {
						$parameters = $rf->getMethod("set".ucwords($property->getName()))->getParameters();
						$paramDef = array();
						$paramVar = array();
						foreach ($parameters as $parameter) {
							$type = "";
							$defValue = "";
							if ($parameter->getClass()) {
								$type = $parameter->getClass()->getName()." ";
							}
							if ($parameter->isDefaultValueAvailable()) {
								$defValue = " = ".($parameter->getDefaultValue() ? $parameter->getDefaultValue() : "null");
							}
							$paramDef[] = $type."\$".$parameter->getName().$defValue;
							$paramVar[] = "\$".$parameter->getName();
						}
						if (sizeof($paramDef) > 1) {
							$paramDef = implode(",", $paramDef);
							$paramVar = implode(",", $paramVar);
						} else {
							$paramDef = $paramDef[0];
							$paramVar = $paramVar[0];
						}
						$classDef .= "public function set".ucwords($property->getName())."(".$paramDef.") {
			      			if (\$this->init()) {
			      				\$this->subject->set".ucwords($property->getName())."(".$paramVar.");
			      			}
			      		}
			      		";
					}
				}
				$classDef .= "}";
				//echo $classDef;
				eval($classDef);
			}
			
			//Manter instancias de Subjects
			$oid = EntityCacher::makeOIDString($object);
			if (isset(self::$proxies[$oid])) {
				$oProxy = &self::$proxies[$oid];
			} else {
				$oProxy = new $proxyClassName($object);
				self::$proxies[$oid] = &$oProxy;
			}	
			return $oProxy;
		}
	}

	/** Gera um hash de estado da entidade
	 * @param object $entity    Entidade para gerar o hash
	 * @return string
	 */
	public static function makeStateString ($entity) {
		$className = get_class($entity);
		if (class_exists($className)  && in_array( $className, get_declared_classes() )) {
			$rf = EntityManager::getReflectionData($className);
			//Propriedades e valores
			$str = array();
			foreach ($rf->getORMProperties() as $property) {
				if ($property->isTransient()) continue;
				if ($property->isCollection()) continue;
				//if ($property->isJoined()) continue;
				//$str[] = $property->getValue($entity);
				
				if ($property->isJoined() && !is_null($property->getValue($entity))) {
					$str[] = EntityCacher::makeOIDString($property->getValue($entity));
				} else {
					$str[] = $property->getValue($entity);
				}
				
			}
		}
		//return implode("#12$|%|$21#",$str);
		return hash('md5',implode("#12$|%|$21#",$str));
	}

	/** Retorna o Loader genérico da classe fornecida
	 * @param string $className    Nome da classe da entidade
	 * @return ORMLoader
	 */
	public static function getLoader($className) {
		if (is_object($className)) $className = get_class($className);
		if (!isset(self::$loaders[$className])) {
			$builder = new ORMLoaderBuilder(true);
			$builder->build($className);
			self::$loaders[$className] = $builder->getRoot();
		}
		return self::$loaders[$className];
	}

	/** Retorna oPersister genérico da classe fornecida
	 * @param string $className    Nome da classe da entidade
	 * @return ORMPersister
	 */
	public static function getPersister($className) {
		if (is_object($className)) $className = get_class($className);
		if (!isset(self::$persisters[$className])) {
			$builder = new ORMPersisterBuilder();
			$builder->build($className);
			self::$persisters[$className] = $builder->getRoot();
		}
		return self::$persisters[$className];
	}

	/** Retorna o Deleter genérico da classe fornecida
	 * @param string $className    Nome da classe da entidade
	 * @return ORMDeleter
	 */
	public static function getDeleter($className) {
		if (is_object($className)) $className = get_class($className);
		if (!isset(self::$deleters[$className])) {
			$builder = new ORMDeleterBuilder();
			$builder->build($className);
			self::$deleters[$className] = $builder->getRoot();
		}
		return self::$deleters[$className];
	}

	/** Experimental
	 * @param $className
	 * @todo Implementar
	 */
	public static function getORMNode($className) {
		if (!isset(self::$nodes[$className])) self::$nodes[$className] = new ORMNode(self::getReflectionData($className));

		return self::$nodes[$className];

	}
	
	public static function updateSubject(&$object) {
		$oid = EntityCacher::makeOIDString($object);
		if (isset(self::$proxies[$oid])) {
			foreach (self::$proxies[$oid] as $proxy) {
				$proxy->init($object);
			}
			self::$proxies[$oid] = null;
			unset(self::$proxies[$oid]);
		}
	}
	
	public static function isProxified($object) {
		return isset(self::$proxies[EntityCacher::makeOIDString($object)]);
	}
	
}
?>
