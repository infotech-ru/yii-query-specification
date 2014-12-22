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

class Join implements SpecificationInterface
{
    /**
     * @var string
     */
    private $relationName;
    /**
     * @var string|null
     */
    private $alias;

    public function __construct($relationName, $alias = null)
    {
        $this->relationName = $relationName;
        $this->alias = $alias ?: $relationName;
    }

    /**
     * @param string $alias
     *
     * @return CDbCriteria
     */
    public function getCriteria($alias)
    {
        return new CDbCriteria([
            'with' => [
                $this->relationName => [
                    'alias' => $this->alias
                ]
            ]
        ]);
    }
}
