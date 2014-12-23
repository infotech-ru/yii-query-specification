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
use CDbCriteria;

abstract class Comparison extends Filter
{
    const EQUALS = '=';
    const NOT_EQUALS = '!=';
    const LESS_THEN = '<';
    const LESS_THEN_OR_EQUALS = '<=';
    const GREATER_THEN = '>';
    const GREATER_THEN_OR_EQUALS = '>=';
    const LIKE = 'LIKE';

    /**
     * @var
     */
    private $column;
    /**
     * @var
     */
    private $value;
    /**
     * @var string
     */
    private $alias;
    /**
     * @var
     */
    private $operator;

    public function __construct($operator, $column, $value, $alias = null)
    {
        $this->operator = $operator;
        $this->column = $column;
        $this->value = $value;
        $this->alias = $alias;
    }

    /**
     * @param CActiveRecord $model
     *
     * @return CDbCriteria
     */
    public function getCriteria(CActiveRecord $model)
    {
        $column = sprintf('%s.%s', $this->alias ?: $model->getTableAlias(false, false), $this->column);
        $paramName = $this->createParameterName($column);

        $criteria = new CDbCriteria();
        $criteria->addCondition(sprintf('%s %s %s', $column, $this->operator, $paramName));
        $criteria->params[$paramName] = $this->value;

        return $criteria;
    }
}
