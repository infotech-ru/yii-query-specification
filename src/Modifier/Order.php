<?php
/*
 * This file is part of the minity/yii-query-specification package.
 *
 * (c) Anton Tyutin <anton@tyutin.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Minity\QuerySpecification\Modifier;

use CDbCriteria;
use Minity\QuerySpecification\SpecificationInterface;

class Order implements SpecificationInterface
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';

    /**
     * @var
     */
    private $column;
    /**
     * @var string
     */
    private $dir;
    /**
     * @var
     */
    private $alias;

    public function __construct($column, $dir = self::DIRECTION_ASC, $alias)
    {
        $this->column = $column;
        $this->dir = $dir;
        $this->alias = $alias;
    }

    /**
     * @param string $alias
     *
     * @return CDbCriteria
     */
    public function getCriteria($alias)
    {
        $criteria = new CDbCriteria();
        $criteria->order = sprintf('%s.%s %s', $this->alias ?: $alias, $this->column, $this->dir);

        return $criteria;
    }
}
