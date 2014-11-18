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

use Minity\QuerySpecification\Logic;
use Minity\QuerySpecification\Filter;
use ReflectionClass;

/**
 * Specification factory
 */
class Spec
{
    static public function andX()
    {
        return self::createSpecWithArray('Minity\QuerySpecification\Logic\AndX', func_get_args());
    }

    static public function orX()
    {
        return self::createSpecWithArray('Minity\QuerySpecification\Logic\OrX', func_get_args());
    }

    static public function not(SpecificationInterface $spec)
    {
        return new Logic\Not($spec);
    }

    static public function eq($column, $value, $alias = 't')
    {
        return new Filter\Equals($column, $value, $alias);
    }

    static public function neq($column, $value, $alias = 't')
    {
        return new Filter\NotEquals($column, $value, $alias);
    }

    static public function lt($column, $value, $alias = 't')
    {
        return new Filter\LessThen($column, $value, $alias);
    }

    static public function lte($column, $value, $alias = 't')
    {
        return new Filter\LessThenOrEquals($column, $value, $alias);
    }

    static public function gt($column, $value, $alias = 't')
    {
        return new Filter\GreaterThen($column, $value, $alias);
    }

    static public function gte($column, $value, $alias = 't')
    {
        return new Filter\GreaterThenOrEquals($column, $value, $alias);
    }

    static public function like($column, $value, $alias = 't')
    {
        return new Filter\Like($column, $value, $alias);
    }

    /**
     * @param $args
     *
     * @return SpecificationInterface
     */
    public static function createSpecWithArray($specClass, $args)
    {
        $reflectionClass = new ReflectionClass($specClass);

        return $reflectionClass->newInstanceArgs($args);
    }
}
