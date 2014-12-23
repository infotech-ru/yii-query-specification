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

class Between extends Filter
{
    /**
     * @var string
     */
    private $column;
    /**
     * @var mixed
     */
    private $from;
    /**
     * @var mixed
     */
    private $to;
    /**
     * @var string
     */
    private $alias;

    public function __construct($column, $from, $to, $alias = null)
    {
        $this->column = $column;
        $this->from = $from;
        $this->to = $to;
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
        $fromParam = self::createParameterName($column);
        $toParam = self::createParameterName($column);

        $criteria = new CDbCriteria();
        $criteria->addCondition(sprintf('%s BETWEEN %s AND %s', $column, $fromParam, $toParam));
        $criteria->params[$fromParam] = $this->from;
        $criteria->params[$toParam] = $this->to;

        return $criteria;
    }
}
