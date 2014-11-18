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

class FilterTest extends PHPUnit_Framework_TestCase
{
    public function testGood()
    {
        $spec = Spec::orX(
            Spec::not(
                Spec::andX(
                    Spec::gt('a', 15),
                    Spec::lte('a', 25)
                )
            ),
            Spec::like('s', 'some%')
        );

        $criteria = $spec->getCriteria();

        $this->assertEquals('(NOT ((t.a > :t_a) AND (t.a <= :t_a_another))) OR (t.s LIKE :t_s)', $criteria->condition);
        $this->assertEquals(array(':t_a' => 15, ':t_a_another' => 25, ':t_s' => 'some%'), $criteria->params);
    }

    public function testAliasInConstructor()
    {
        $spec = Spec::orX(
            Spec::not(
                Spec::andX(
                    Spec::gt('a', 15, 'o'),
                    Spec::lte('a', 25, 'o')
                )
            ),
            Spec::like('s', 'some%', 'a')
        );

        $criteria = $spec->getCriteria();

        $this->assertEquals('(NOT ((o.a > :o_a) AND (o.a <= :o_a_another))) OR (a.s LIKE :a_s)', $criteria->condition);
        $this->assertEquals(array(':o_a' => 15, ':o_a_another' => 25, ':a_s' => 'some%'), $criteria->params);
    }

    public function testAliasInGetCriteria()
    {
        $spec = Spec::orX(
            Spec::not(
                Spec::andX(
                    Spec::gt('a', 15),
                    Spec::lte('a', 25)
                )
            ),
            Spec::like('s', 'some%')
        );

        $criteria = $spec->getCriteria('b');

        $this->assertEquals('(NOT ((b.a > :b_a) AND (b.a <= :b_a_another))) OR (b.s LIKE :b_s)', $criteria->condition);
        $this->assertEquals(array(':b_a' => 15, ':b_a_another' => 25, ':b_s' => 'some%'), $criteria->params);
    }
}
