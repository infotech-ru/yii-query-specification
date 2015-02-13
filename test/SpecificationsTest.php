<?php
/*
 * This file is part of the minity/yii-query-specification package.
 *
 * (c) Anton Tyutin <anton@tyutin.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Minity\QuerySpecification\Modifier\Order;
use Minity\QuerySpecification\Spec;
use Minity\QuerySpecification\SpecificationInterface;

class SpecificationsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideFilterGood
     */
    public function testFilterGood(SpecificationInterface $spec, $expectedWhere, $expectedParams)
    {
        $criteria = $spec->getCriteria($this->createModel('t'));
        $this->assertEquals($expectedWhere, $criteria->condition);
        $this->assertEquals($expectedParams, $criteria->params);
    }

    public function provideFilterGood()
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
                Spec::between('h', 1, 2),
                't.h BETWEEN :t_h AND :t_h_another',
                array(':t_h' => 1, ':t_h_another' => 2)
            ),
            array(
                Spec::between('h', 1, 2, 'tab'),
                'tab.h BETWEEN :tab_h AND :tab_h_another',
                array(':tab_h' => 1, ':tab_h_another' => 2)
            ),
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

    public function testInCondition()
    {
        $list = array(1, 2, 3);
        $currentParams = CDbCriteria::$paramCount;

        $spec = Spec::in('k', $list, 't');
        $criteria = $spec->getCriteria($this->createModel('t'));

        foreach ($list as $k => $v) {
            $list[':ycp' . $currentParams++] = $v;
            unset($list[$k]);
        }

        $this->assertEquals(
            't.k IN (' . implode(', ', array_keys($list)) . ')',
            $criteria->condition
        );
        $this->assertEquals($list, $criteria->params);
    }

    public function testOrder()
    {
        $spec = Spec::order('a');
        $this->assertEquals('t.a ASC', $spec->getCriteria($this->createModel('t'))->order);

        // order specifications will be merged by CDbCriteria#mergeWith() in reverse order
        $spec = Spec::andX(Spec::order('b', Order::DIRECTION_DESC, 'g'), $spec);
        $this->assertEquals('t.a ASC, g.b DESC', $spec->getCriteria($this->createModel('t'))->order);
    }

    public function testJoin()
    {
        $spec1 = Spec::join('a');
        $this->assertEquals(
            array('a' => array('alias' => null)),
            $spec1->getCriteria($this->createModel('t'))->with
        );

        $spec2 = Spec::join('b', 'c');
        $this->assertEquals(
            array('b' => array('alias' => 'c')),
            $spec2->getCriteria($this->createModel('t'))->with
        );

        $spec = Spec::andX($spec1, $spec2);
        $this->assertEquals(
            array('a' => array('alias' => null), 'b' => array('alias' => 'c')),
            $spec->getCriteria($this->createModel('t'))->with
        );
    }

    /**
     * @param $alias
     *
     * @return CActiveRecord|PHPUnit_Framework_MockObject_MockObject
     */
    private function createModel($alias)
    {
        $model = $this->getMockBuilder('CActiveRecord')
            ->setMethods(array('getTableAlias'))
            ->disableOriginalConstructor()
            ->getMock();
        $model->expects($this->any())->method('getTableAlias')->willReturn($alias);

        return $model;
    }
}
