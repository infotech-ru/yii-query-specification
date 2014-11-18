<?php
/*
 * This file is part of the minity/yii-query-specification package.
 *
 * (c) Anton Tyutin <anton@tyutin.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Minity\QuerySpecification\Logic;

use CDbCriteria;
use Minity\QuerySpecification\SpecificationInterface;

abstract class LogicX implements SpecificationInterface
{
    /**
     * @var string
     */
    private $operator;

    /**
     * @var SpecificationInterface[]
     */
    private $specs = array();

    public function __construct($operator, $specs)
    {
        $this->operator = $operator;
        $this->specs = $specs;
    }

    /**
     * @param string $alias
     *
     * @return CDbCriteria
     */
    public function getCriteria($alias)
    {
        $criteria = new CDbCriteria();

        foreach ($this->specs as $spec) {
            $criteria->mergeWith($spec->getCriteria($alias), $this->operator);
        }

        return $criteria;
    }
}
