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

class In extends Filter
{
    /**
     * @var string
     */
    private $column;
    /**
     * @var array
     */
    private $list;
    /**
     * @var string
     */
    private $alias;

    public function __construct($column, $list, $alias = null)
    {
        $this->column = $column;
        $this->list = $list;
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
        $criteria->addInCondition($column, $this->list);

        return $criteria;
    }
}
