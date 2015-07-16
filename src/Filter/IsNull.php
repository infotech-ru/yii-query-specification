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

use Minity\QuerySpecification\SpecificationInterface;
use CActiveRecord;
use CDbCriteria;

class IsNull implements SpecificationInterface
{
    private $column;

    private $alias;

    public function __construct($column, $alias = 't')
    {
        $this->column = $column;
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

        $criteria = new CDbCriteria();
        $criteria->addCondition(sprintf('%s IS NULL', $column));

        return $criteria;
    }
}
