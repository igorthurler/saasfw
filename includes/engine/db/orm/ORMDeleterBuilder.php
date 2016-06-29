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
 * File: ORMDeleterBuilder.php
 **/

import('engine.db.orm.ORMBuilder');

/** Constroi uma operacao de exclusao da entidade
 * @author Silas R. N. Junior
 */
class ORMDeleterBuilder extends ORMBuilder {

	/** Constroi o proximo mapeamento
	 * @param string $className    Nome da classe
	 * @param array $path    Caminho desde o objeto raiz
	 * @param ReflectionORMProperty $ownerClassProperty    Propriedade da classe conteiner que referencia esta classe
	 * @param ORM $ownerClassORM    Mapeamento da classe conteiner
	 * @param int $depth    Contador de Profundidade de recursao
	 * @return ORM
	 */
	public function build($className, $path = null,ReflectionORMProperty $ownerClassProperty = null, ORM &$ownerClassORM = null, $depth = null) {

		$enqueue = true;
		
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
		
		//Deleter
		$ORM = new ORMDeleter($reflectionClass,ORMBuilder::getNextAlias(),$strPath,$depth);
		
		//Processa arvore
		$deleteAfter = array();
		foreach ($reflectionClass->getJoins() as $join) {
			//Mapeamentos bidirecionais
			if ($join->getMappedBy()) { //Referenciado
				if ($ownerClassProperty && ($join->getMappedBy() == $ownerClassProperty->getName() && ($join->getType() == $ownerClassORM->getRflctORM()->getName()))) {
					continue;
				} else {
					if (!$join->getCascade() || ($join->getCascade() != CascadeType::NONE && $join->getCascade() != CascadeType::DELETE) ) {
						$joinORM = $this->navigateAssociation($ORM, $join, $path, $depth);
					}
				}
			} else { //Referenciador
				//Autorelacionamento
				if ($join->isManyToOne() && $join->getType() == $className && !$ownerClassORM) {
					$enqueue = false;
				}
				if ($ownerClassORM && $ownerClassORM->getRflctORM()->isMapping($reflectionClass->getName(),$join->getName())) {
					continue;
				} else {
					if (!$join->getCascade() || (($join->getCascade() == CascadeType::DELETE)||($join->getCascade() == CascadeType::ALL)) ) $deleteAfter[] = $join;
				}
			}
		}
		
		//Root & Next
		if ($enqueue) {
			$this->addToQueue($ORM);
		}
		
		//Deletar mapeamentos dependentes
		foreach ($deleteAfter as $join) {
			$joinORM = $this->navigateAssociation($ORM, $join, $path, $depth);
		}
		
		//Heranca
		$null = null;
		$parentORM = $this->navigateInheritance($ORM, $null, $null,$path,$depth);

		return $ORM;
	}
}

?>
