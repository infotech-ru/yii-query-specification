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

class OrX extends LogicX
{
    public function __construct()
    {
        parent::__construct('OR', func_get_args());
    }
}
