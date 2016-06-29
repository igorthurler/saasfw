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
 * File: ORMLoadBuilder.php
 **/

import('engine.db.orm.ORMBuilder');

/** Constroi uma operacao de carregamento da entidade
 * @author Silas R. N. Junior
 */
class ORMLoaderBuilder extends ORMBuilder {

	/** Filtro a ser utilizado
	 * @var EntityFilter
	 */
	private $filter;

	/**
	 * @param boolean $reset    Flag para reiniciar contador de alias
	 * @param EntityFilter $filter    Filtro para overrides em fetches
	 */
	public function ORMLoaderBuilder($reset = false, EntityFilter $filter = null) {
		if ($reset) {
			ORMBuilder::resetCounter();
		}
		$this->filter = $filter;
	}

	/** Constroi o proximo mapeamento
	 * @param string $className   Nome da classe
	 * @param array $path   Caminho desde o objeto raiz
	 * @param ReflectionORMProperty $ownerClassProperty   Propriedade da classe conteiner que referencia esta classe
	 * @param ORM $ownerClassORM   Mapeamento da classe conteiner
	 * @param int $depth   Contador de Profundidade de recursao
	 * @return ORMLoader
	 */
	public function build($className, $path = null,ReflectionORMProperty $ownerClassProperty = null, ORM &$ownerClassORM = null, $depth = null) {

		//Classe de reflexao
		$reflectionClass = EntityManager::getReflectionData($className);

		//Testa o depth para referencias ciclicas
		$this->checkRecursion($path, $className, $depth);
		if ($depth < 0) return null;

		//Prepara o path
		if($path == null) {
			$strPath = "root#";
			$path = array($strPath => $className);
		} else {
			end($path);
			$strPath = key($path);
		}

		//Loader
		$ORM = new ORMLoader($reflectionClass,ORMBuilder::getNextAlias(),$strPath,$depth);

		//Heranca
		$parentORM = $this->navigateInheritance($ORM, $ownerClassProperty, $ownerClassORM,$path,$depth);

		//Encadeamento
		if ($ownerClassORM) { //Entidade referenciada
			if (isset($parentORM)) { //Subclasse de Heranca
				//LAZY OU FETCH?
				if ((($this->filter && $path) && $this->filter->getFetchMode($strPath) == FetchType::FETCH) || (!isset($ownerClassProperty) || !$ownerClassProperty->getFetch() || $ownerClassProperty->getFetch() == FetchType::FETCH)) {
					$this->getRoot()->setNext($ORM);
					$ORM->addAssociation(new ORMAssociation($parentORM,$ORM,"super",JoinType::INNER_JOIN));
				} /*else if ((($this->filter && $path) && $this->filter->getFetchMode($strPath) == FetchType::LAZY) || ($ownerClassProperty->getFetch() == FetchType::LAZY)) {
					//$ownerClassORM->addLazyLoad($ORM);
				}*/

			} else {
				//LAZY OU FETCH?
				if ((($this->filter && $path) && $this->filter->getFetchMode($strPath) == FetchType::FETCH) || (!isset($ownerClassProperty) || !$ownerClassProperty->getFetch() || $ownerClassProperty->getFetch() == FetchType::FETCH)) {

					$this->addToQueue($ORM);
					
				} else if ((($this->filter && $path) && $this->filter->getFetchMode($strPath) == FetchType::LAZY) || ($ownerClassProperty->getFetch() == FetchType::LAZY)) {
				
					$ownerClassORM->addLazyLoad($ORM);
				}

				//Associa com a entidade anterior se nao for parte de uma chave estrangeira
				if (!$ownerClassProperty->isForeignKey()) {
					$ownerClassORM->addAssociation(new ORMAssociation($ownerClassORM,$ORM,$ownerClassProperty));
				}
			}
		} else { //Entidade normal
			$this->addToQueue($ORM);
			if (isset($parentORM)) { //Subclasse de Heranca
				$ORM->addAssociation(new ORMAssociation($parentORM,$ORM,"super",JoinType::LEFT_JOIN));
			}
		}

		//Indices Chave Estrangeira
		foreach ($reflectionClass->getIndexes() as $index) {
			if ($index->isForeignKey()) {
				$indexORM = $this->navigateAssociation($ORM, $index, $path, $depth);
				if(!is_null($indexORM)) {
					$ORM->addAssociation(new ORMAssociation($ORM,$indexORM,$index,JoinType::INNER_JOIN));
				}
			}
		}

		//Processa arvore se anterior for fetch
		if ((($this->filter && $path) && $this->filter->getFetchMode($strPath) == FetchType::FETCH) || (!isset($ownerClassProperty) || !$ownerClassProperty->getFetch() || $ownerClassProperty->getFetch() == FetchType::FETCH)) {
			foreach ($reflectionClass->getJoins() as $join) {
				if ($join->isIndex()) continue; //Indices ja foram tratados
				//Mapeamentos bidirecionais
				if ($join->getMappedBy()) {
					if ($ownerClassProperty && $join->getMappedBy() == $ownerClassProperty->getName()) {
						if ($join->getType() == $ownerClassORM->getRflctORM()->getName()) continue;
					}
				} else {
					if ($ownerClassORM && $ownerClassORM->getRflctORM()->isMapping($reflectionClass->getName(),$join->getName())) continue;
				}
				//Proximo nodo
				$joinORM = $this->navigateAssociation($ORM, $join, $path, $depth);
			}
		}

		return (isset($parentORM) ? $parentORM :  $ORM); //retornando o ORM do Pai para associacoes em chave estrangeira
	}
}
?>
