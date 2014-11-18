<?php
/*
 * This file is part of the minity/yii-query-specification package.
 *
 * (c) Anton Tyutin <anton@tyutin.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Minity\QuerySpecification;

use CDbCriteria;

interface SpecificationInterface
{
    /**
     * @param string $alias
     *
     * @return CDbCriteria
     */
    public function getCriteria($alias);
}
