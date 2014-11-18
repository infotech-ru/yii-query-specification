<?php
/*
 * This file is part of the minity/yii-query-specification package.
 *
 * (c) Anton Tyutin <anton@tyutin.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Minity\QuerySpecification\Spec;
use Minity\QuerySpecification\SpecificationInterface;

class FilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideGood
     */
    public function testGood(SpecificationInterface $spec, $expectedWhere, $expectedParams)
    {
        $criteria = $spec->getCriteria('t');
        $this->assertEquals($expectedWhere, $criteria->condition);
        $this->assertEquals($expectedParams, $criteria->params);
    }

    public function provideGood()
    {
        return array(
            // $spec, $expectedWhere, $expectedParams
            array(Spec::eq('a', 2), 't.a = :t_a', array(':t_a' => 2)),
            array(Spec::eq('a', 2, 'tab'), 'tab.a = :tab_a', array(':tab_a' => 2)),
            array(Spec::neq('b', 2, 'tab'), 'tab.b != :tab_b', array(':tab_b' => 2)),
            array(Spec::lt('c', 2, 'tab'), 'tab.c < :tab_c', array(':tab_c' => 2)),
            array(Spec::lte('d', 2, 'tab'), 'tab.d <= :tab_d', array(':tab_d' => 2)),
            array(Spec::gt('e', 2, 'tab'), 'tab.e > :tab_e', array(':tab_e' => 2)),
            array(Spec::gte('f', 2, 'tab'), 'tab.f >= :tab_f', array(':tab_f' => 2)),
            array(Spec::like('g', 'str%', 'tab'), 'tab.g LIKE :tab_g', array(':tab_g' => 'str%')),
            array(
                Spec::andX(Spec::eq('i', 'blah'), Spec::neq('i', 'hey')),
                '(t.i = :t_i) AND (t.i != :t_i_another)',
                array(':t_i' => 'blah', ':t_i_another' => 'hey')
            ),
            array(
                Spec::orX(Spec::eq('j', 'blah'), Spec::eq('j', 'hey')),
                '(t.j = :t_j) OR (t.j = :t_j_another)',
                array(':t_j' => 'blah', ':t_j_another' => 'hey')
            ),
        );
    }
}
