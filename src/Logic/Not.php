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

use Minity\QuerySpecification\SpecificationInterface;

class Not implements SpecificationInterface
{
    /**
     * @var SpecificationInterface
     */
    private $spec;

    public function __construct(SpecificationInterface $spec)
    {
        $this->spec = $spec;
    }

    /**
     * @param string $alias (not used)
     *
     * @return array string
     */
    public function getCriteria($alias = null)
    {
        $criteria = $this->spec->getCriteria();
        $criteria->condition = 'NOT (' . $criteria->condition . ')';

        return $criteria;
    }
}
