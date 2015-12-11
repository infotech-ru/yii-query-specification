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


use CActiveRecord;
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
    /**
     * @var bool|null
     */
    private $together;

    public function __construct($relationName, $alias = null, $together = false)
    {
        $this->relationName = $relationName;
        $this->alias = $alias;
        $this->together = $together;
    }

    /**
     * @param CActiveRecord $model
     *
     * @return CDbCriteria
     */
    public function getCriteria(CActiveRecord $model)
    {
        return new CDbCriteria([
            'together' => $this->together,
            'with' => [
                $this->relationName => [
                    'alias' => $this->alias
                ]
            ]
        ]);
    }
}
