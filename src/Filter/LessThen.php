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

class LessThen extends Comparison
{
    public function __construct($column, $value, $alias = 't')
    {
        parent::__construct(self::LESS_THEN, $column, $value, $alias);
    }
}
