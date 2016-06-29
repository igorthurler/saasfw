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
 * File: FilterGroupBy.php
 **/

import('engine.db.filter.IFilterCondition');

/** Agrupamento de resultados
 * @author Silas R. N. Junior
*/
class FilterGroupBy implements IFilterCondition {

    /** Propriedade para agrupar os resultados
     * @var string
     */
    private $propertyName;

    /**
     * @param string $propertyName   Nome da propriedade
     */
    public function FilterGroupBy($propertyName) {
        $this->propertyName = $propertyName;
    }

    /** Define a propriedade para agrupamento dos resultados
     * @param string $propertyName   Nome da propriedade
     * @return void
     */
    public static function groupProperty($propertyName) {
        return new FilterGroupBy($propertyName);
    }

    public function getPath() {
        return $this->propertyName;
    }

    /** Gera o codigo sql
     * @param EntityFilter $entityFilter
     * @return string
     */
    public function toSql(EntityFilter $entityFilter) {
        //[TODO] criar funcao
        $splitPath = explode(".",$this->propertyName);
        if (count($splitPath) > 1 ) {
            $aliases = $entityFilter->getAliases();
            $class = $aliases[$splitPath[0]]['type'];
            $property = $splitPath[1];
            $alias = $splitPath[0];
        } else {
            $class = $entityFilter->getClass()->getName();
            $property = $this->propertyName;
            $alias = $entityFilter->getAlias();
        }
        $rf = EntityManager::getReflectionData($class);
        $refrTable = $rf->getTableName();
        if ($rf->hasORMProperty($property)) {
            $refrProperty = $rf->getORMProperty($property);
        } else {
            throw new Exception("[ORM] A classe ".$this->class." nao possui a propriedade ".$currentProperty);
        }

        return $entityFilter->getDAO()->getDriver()->formatTable($alias.$refrTable).".".$entityFilter->getDAO()->getDriver()->formatField($refrProperty->getColumnName());
        /*
         * [HACK] hasProperty nao funciona da forma desejada.
        * [TODO] criar funcao
        /

        $props = EntityManager::getReflectionData($class)->getORMProperties();
        $currentProperty = $property;
        $has = false;
        foreach ($props as $p) {
        if ($p->name == $currentProperty) {
        $has = true;
        $currentReflectionProperty = $p;
        $class = $rf->getName();
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
        $class = $rf->getName();
        $has = true;
        break 2;
        }
        }
        }
        if (!$has) {
        throw new Exception("[ORM] A classe ".$this->class." nao possui a propriedade ".$currentProperty);
        }
        }
        /*
        * [HACK] Fim do hack
        */

    }
}

?>
