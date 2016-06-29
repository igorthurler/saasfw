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
 * File: ORMDeleter.php
 **/

import('engine.db.orm.ORM');

/** Classe de exclusao das entidades
 * @author Silas R. N. Junior
 */
class ORMDeleter extends ORM {

	/** Estrategia cascade para o mapeamento
	 * @var mixed
	 */
	private $cascade;

	/**
	 * @param ReflectionORMClass $rflctORM
	 * @param string $alias    Apelido da entidade
	 * @param string $path    Caminho da entidade na arvore
	 * @param int $depth    Profundidade do relacionamento
	 */
	public function ORMDeleter(ReflectionORMClass $rflctORM, $alias, $path, $depth = null) {
		$this->setRflctORM($rflctORM);
		$this->setAlias($alias);
		$this->setPath($path);
		$this->setDepth($depth);
	}

	/** Retorna a estrategia de propagacao das operacoes de persistencia
	 * @return mixed
	 */
	public function getCascade() {
		return $this->cascade;
	}

	/** Define a estrategia de propagacao das operacoes de persistencia
	 * @param mixed $newCascade
	 * @return void
	 */
	public function setCascade($newCascade) {
		$this->cascade = $newCascade;
	}

	/** Constroi a string SQL
	 * @param DbDriver $driver    Driver do banco de dados
	 * @param object $entity    Objeto da entidade
	 * @return void
	 */
	public function buildSQL(ORMRequest $request) {

		$driver = $request->getDriver();
		$entity = $request->getEntity();

		$currentEntity = $this->fetchEntity($entity);
		if (!$currentEntity) return;

		if ($this->getCached($driver)) {
			$sql = $this->getCached($driver);
		} else {

			$sql = "DELETE FROM ".$driver->formatTable($this->getRflctORM()->getTableName());
			$current = $this;

			//indices
			$indexes = $this->getRflctORM()->getIndexes();
			$where = array();
			foreach ($indexes as $index) {
				if ($index->isForeignKey()) {
					$foreignKeys = $index->getForeignIndexORMProperties();
					foreach ($foreignKeys as $fkIndex) {
						$where[] = $driver->formatField($fkIndex->getColumnName())." = ?";
					}
				} else {
					$where[] = $driver->formatField($index->getColumnName())." = ?";
				}
			}
	
			$sql .= " WHERE (".implode(" AND ",$where).")";
			$this->setCached($sql,$driver);
		}
		return $sql;
	}
	
	private function fillSQL(ORMRequest $request,$sql) {
		$driver = $request->getDriver();
		$entity = $request->getEntity();
		
		$currentEntity = $this->fetchEntity($entity);
		if (!$currentEntity) return;
		//Preenchendo
		$pos = 0;

		//indices
		foreach ($this->getRflctORM()->getIndexes() as $index) {
			if ($index->isForeignKey()) {
				$foreignKeys = $index->getForeignIndexORMProperties();
				foreach ($foreignKeys as $fkIndex) {
					$pos = strpos($sql, "?", $pos);
					if ($pos === false) break;
					$value = $driver->formatValue($fkIndex->getType(),trim($fkIndex->getValue($entity)));
					$sql = substr($sql, 0, $pos) .$value. substr($sql, $pos + 1);
					$pos += strlen($value);
				}
			} else {
				$pos = strpos($sql, "?", $pos);
				if ($pos === false) break;
				$value = $driver->formatValue($index->getType(),$index->getValue($currentEntity));
				$sql = substr($sql, 0, $pos) .$value. substr($sql, $pos + 1);
				$pos += strlen($value);
			}
		}
		return $sql;
	}

	/** Remove as colecoes
	 * @param DbDriver $driver    Driver do Banco de dados
	 * @param object $entity    Entidade a ser sincronizada
	 */
	public function deleteCollections(ORMRequest $request) {

		$driver = $request->getDriver();
		$entity = $request->getEntity();

		$currentEntity = $this->fetchEntity($entity);
		if (!$currentEntity) return;

		foreach ($this->getRflctORM()->getCollections() as $collection) {
			$deleterBuilder = new ORMDeleterBuilder();
			$deleter = $deleterBuilder->build($collection->getTargetEntity(),null,$collection,$this,$this->getDepth());
				
			$collectionRflctORM = EntityManager::getReflectionData($collection->getTargetEntity());
			$collectionInstance = (is_null($collection->getValue($currentEntity)) ? new Collection(array()) : $collection->getValue($currentEntity));
				
			if ($collection->isOneToMany()) {
				$mappedProperty = $collectionRflctORM->getORMProperty($collection->getMappedBy());

				if ($collection->isDeleteOrphan() ||
				($collection->getCascade() == CascadeType::ALL)||
				($collection->getCascade() == CascadeType::DELETE)) {

					foreach($collectionInstance->toArray() as $current) {
						$deleter->sync($request->getSubRequest($current));
					}
				} else {
					if (!is_null($collectionInstance) && $collectionInstance->size() > 0) {
						throw new Exception("[Runtime|ORM] Um objeto da classe [".$this->getRflctORM()->getName()."] nao pode ser removido pois possui itens na colecao da propriedade OneToMany [{$collection->getName()}] e nao define uma estrategia de CASCADE adequada (ALL,DELETE).");
					}
				}
			} else if ($collection->isManyToMany()) {

				$joinTable = $collection->getJoinTable();
				$joinColumns = (is_array($collection->getJoinColumns()) ? $collection->getJoinColumns() :  array($collection->getJoinColumns()));
				$inverseJoinColumns = (is_array($collection->getInverseJoinColumns()) ? $collection->getInverseJoinColumns() :  array($collection->getInverseJoinColumns()));
				if ($this->getRflctORM()->getInheritanceStrategy() == InheritanceType::TABLE_PER_CLASS) {
					$parent = $this->getRflctORM()->getParentORMClass();
					while ($parent->getParentClass()) {
						$parent = $parent->getParentORMClass();
					}
					$thisIndexes = $parent->getIndexes();
				} else {
					$thisIndexes = $this->getRflctORM()->getIndexes();
				}
				
				$collectionIndexes = $collectionRflctORM->getIndexes();

				foreach($collectionInstance->toArray() as $current) {
					$values = array();
					$fields = array();
					$sql = "DELETE FROM ".$driver->formatTable($joinTable);
						
					//Owner Side
					for ($i = 0; $i < sizeof($joinColumns); $i++) {
						$fields[] = $driver->formatField($joinColumns[$i])." = ".$driver->formatValue($thisIndexes[$i]->getType(),$thisIndexes[$i]->getValue($entity));
					}
						
					//Owned Side
					for ($i = 0; $i < sizeof($inverseJoinColumns); $i++) {
						$fields[] = $driver->formatField($inverseJoinColumns[$i])." = ".$driver->formatValue($collectionIndexes[$i]->getType(),$collectionIndexes[$i]->getValue($current));
					}
						
					$sql = $sql." WHERE (".implode(" AND ",$fields).")";
					$driver->run($sql);
						
					//Verificar a bidirecionalidade
					if (!$collection->getMappedBy()) {
						if ($collectionRflctORM->isMapping($this->getRflctORM()->getName(),$collection->getName())) {
							$inverseCollectionRflctORM = $collectionRflctORM->getORMProperty($collectionRflctORM->getMapped($this->getRflctORM()->getName(),$collection->getName()));
						} else {
							//Unidirecional
							$inverseCollectionRflctORM = false;
						}
					} else {
						$inverseCollectionRflctORM = $collectionRflctORM->getORMProperty($collection->getMappedBy());
					}
						
					if ($inverseCollectionRflctORM) {
						$inverseCollectionInstance = $inverseCollectionRflctORM->getValue($current);
						if (!is_null($inverseCollectionInstance)) {
							if ($inverseCollectionInstance->contains($entity)) {
								$inverseCollectionInstance->remove($entity);
								$inverseCollectionInstance->resetState($entity);
							} else {
								throw new ORMException("[Runtime] Erro de consistencia. Um objeto contido na colecao [".$this->getRflctORM()->getName().".".$collection->getName()."] existe somente em um dos lados do relacionamento");
							}
						} else {
							if (is_null($inverseCollectionInstance)) throw new ORMException("[Runtime] Erro de consistencia. O relacionamento bidirecional [".$this->getRflctORM()->getName().".".$collection->getName()."] possui uma instancia de colecao somente em um dos lados do relacionamento");
						}
					}
					
					//Remover o item da colecao
					$collectionInstance->remove($current);
						
					if (($collection->getCascade() == CascadeType::ALL)||
					($collection->getCascade() == CascadeType::DELETE)) {
						
						
						if ($request->hasProcessed($current)) continue; //pulando os processados

						//Verifica cache
						If (EntityManager::getCacher()->lookup(EntityManager::getCacher()->getOIDString(($current instanceof IEntityContainer ? $current->getSubject() : $current)))) {
							$current = ($current instanceof IEntityContainer ? $current->getSubject() : $current);
							$deleter->sync($request->getSubRequest($current));
						}
					}
				}
				$collectionInstance->clear();
				$collectionInstance->resetState();
			}
		}
	}
	
	/** Executa a sincronia da(s) entidade(as) com o banco de dados
	 * @param DbDriver $driver    Driver do banco de dados
	 * @param object $entity   Entidade a ser sincronizada
	 * @return void
	 */
	public function sync(ORMRequest $request) {

		$driver = $request->getDriver();
		$entity = $request->getEntity();

		$current = $this;
		while($current) {
				
			$currentEntity = $current->fetchEntity($entity);
			//Nulo? Ja processado? Continue
			if (!$current->getRflctORM()->getParentClass() && (is_null($currentEntity) || 
				$request->hasProcessed($currentEntity,$current->getRflctORM()->getName()))) {
				$current = $current->getNext();
				continue;
			}
			//Proxy? Talvez tenhamos que inicializa-lo
			if ($currentEntity instanceof IEntityContainer) {
				if ($currentEntity->init()) $currentEntity = $currentEntity->getSubject();
			}

			$request->setProcessed($currentEntity,$current->getRflctORM()->getName());
			$current->deleteCollections($request);
			$sql = $current->buildSQL($request);
			$preparedSQL = $current->fillSQL($request,$sql);
			$driver->run($preparedSQL);
			EntityManager::getCacher()->remove(EntityManager::getCacher()->getOIDString($currentEntity));
			
			//Remover referencias - TODO criar metodo proprio [beta] 
			foreach ($current->getRflctORM()->getJoins() as $join) {
				
				$joinEntity = $join->getValue($currentEntity);
				$joinRflctORM = EntityManager::getReflectionData($join->getType());
				
				if (isset($joinEntity)) {
					if ($join->getMappedBy()) {
						$mappedProperty = $joinRflctORM->getORMProperty($join->getMappedBy());
					} else {
						if ($joinRflctORM->isMapping($current->getRflctORM()->getName(), $join->getName())) {
							$mappedProperty = $joinRflctORM->getORMProperty($joinRflctORM->getMapped($current->getRflctORM()->getName(), $join->getName()));
						}
					}
					
					if ($join->isOneToOne()) {
						if (isset($mappedProperty)) {
							//$mappedProperty->setValue($joinEntity, null);
						}
					} else if ($join->isManyToOne()) {
						if (isset($mappedProperty)) {
							$joinedEntityCollection = $mappedProperty->getValue($joinEntity);
							if (isset($joinedEntityCollection) && $joinedEntityCollection->contains($currentEntity)) {
								$joinedEntityCollection->remove($currentEntity);
								$joinedEntityCollection->resetState($currentEntity);
							}
						} 
					}
				}
			}
			
			//Remover indices
			if (!$current->getRflctORM()->getParentClass()) {
				foreach ($current->getRflctORM()->getIndexes() as $index) {
					if ($index->isForeignKey()) {
						continue;
					} else {
						$index->setValue($currentEntity,null);
					}
				}
			}
			$current = $current->getNext();
		}
	}
}

?>
