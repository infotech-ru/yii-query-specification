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

abstract class Filter implements SpecificationInterface
{
    static private $paramsCounters = array();
    static private $paramsSuffixes = array('', '_another', '_thirdone', '_fourthone');

    /**
     * @param $column
     *
     * @return string
     */
    public function createParameterName($column)
    {
        $paramName = $this->createParameterBaseName($column);
        if (!isset(self::$paramsCounters[$paramName])) {
            self::$paramsCounters[$paramName] = 0;
        }

        $paramWithPrefix = $paramName . @self::$paramsSuffixes[self::$paramsCounters[$paramName]]
            ?: '_' . self::$paramsCounters[$paramName];

        self::$paramsCounters[$paramName]++;

        return $paramWithPrefix;
    }

    /**
     * @param $column
     *
     * @return string
     */
    public function createParameterBaseName($column)
    {
        return ':' . strtr($column, array('.' => '_', '`' => '', ' ' => ''));
    }
}
