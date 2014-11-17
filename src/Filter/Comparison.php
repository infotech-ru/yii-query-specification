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

use CDbCriteria;
use Minity\QuerySpecification\SpecificationInterface;

abstract class Comparison implements SpecificationInterface
{
    const EQUALS = '=';
    const NOT_EQUALS = '!=';
    const LESS_THEN = '<';
    const LESS_THEN_OR_EQUALS = '<=';
    const GREATER_THEN = '>';
    const GREATER_THEN_OR_EQUALS = '>=';
    const LIKE = 'LIKE';

    static private $paramsCounters = array();
    static private $paramsSuffixes = array('', '_another', '_thirdone', '_fourthone');

    /**
     * @var
     */
    private $column;
    /**
     * @var
     */
    private $value;
    /**
     * @var string
     */
    private $alias;
    /**
     * @var
     */
    private $operator;

    public function __construct($operator, $column, $value, $alias = 't')
    {
        $this->operator = $operator;
        $this->column = $column;
        $this->value = $value;
        $this->alias = $alias;
    }

    /**
     * @param string $alias
     *
     * @return CDbCriteria
     */
    public function getCriteria($alias = null)
    {
        $column = sprintf('%s.%s', $alias ?: $this->alias, $this->column);
        $paramName = $this->createParameterName($column);

        $criteria = new CDbCriteria();
        $criteria->addCondition(sprintf('%s %s %s', $column, $this->operator, $paramName));
        $criteria->params[$paramName] = $this->value;

        return $criteria;
    }

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
