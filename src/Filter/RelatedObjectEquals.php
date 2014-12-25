<?php
/*
 * This file is part of the minity/yii-query-specification package.
 *
 * (c) Anton Tyutin <anton@tyutin.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Minity\QuerySpecification\Filter;

use CActiveRecord;
use CActiveRelation;
use CBelongsToRelation;
use CDbCriteria;
use InvalidArgumentException;
use Minity\QuerySpecification\SpecificationInterface;
use RuntimeException;

class RelatedObjectEquals implements SpecificationInterface
{

    /**
     * @var CActiveRecord
     */
    private $object;
    /**
     * @var string
     */
    private $relationName;

    public function __construct($relationName, CActiveRecord $object)
    {
        if (!$relationName) {
            throw new InvalidArgumentException('Relation name should not be empty');
        }

        $this->object = $object;
        $this->relationName = $relationName;
    }

    /**
     * @param CActiveRecord $model
     *
     * @return CDbCriteria
     */
    public function getCriteria(CActiveRecord $model)
    {
        $criteria = new CDbCriteria();

        /** @var CActiveRelation $lastRelation */
        $lastRelation = new CActiveRelation($model->getTableAlias(false, false), '', '');
        /** @var CActiveRelation $prevRelation */
        $prevRelation = null;

        $relBaseModel = $model;
        foreach (explode('.', $this->relationName) as $relationName) {
            $prevRelation = $lastRelation;

            $lastRelation = $relBaseModel->getActiveRelation($relationName);

            if (!$lastRelation) {
                throw new RuntimeException(
                    sprintf('Relation "%s" is not defined for model %s', $relationName, get_class($relBaseModel))
                );
            }

            $relBaseModel = CActiveRecord::model($lastRelation->className);
        }

        $with = $lastRelation instanceof CBelongsToRelation
            ? implode('.', array_slice(explode('.', $this->relationName), 0, -1))
            : $this->relationName;

        if ($with) {
            $criteria->with[] = $with;
        }

        if ($lastRelation instanceof CBelongsToRelation) {
            $columnNames = $lastRelation->foreignKey;
            $alias = $prevRelation->alias ?: $prevRelation->name;
        } else {
            $columnNames = $relBaseModel->getTableSchema()->primaryKey;
            $alias = $lastRelation->alias ?: $lastRelation->name;
        }

        $criteria->addColumnCondition(array_combine(
            array_map(
                function ($name) use ($alias) {
                    return sprintf('%s.%s', $alias, $name);
                },
                $this->normalizeColumnNamesArray($columnNames)
            ),
            (array)$this->object->primaryKey ?: [null]
        ));

        return $criteria;
    }

    /**
     * @param $columnNames
     *
     * @return array
     */
    private function normalizeColumnNamesArray($columnNames)
    {
        return is_array($columnNames)
            ? $columnNames
            : array_map('trim', explode(',', $columnNames));
    }
}

