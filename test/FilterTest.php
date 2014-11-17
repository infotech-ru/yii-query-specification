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
            Spec::andX(
                Spec::gt('a', 15),
                Spec::lte('a', 25)
            ),
            Spec::like('s', 'some%')
        );

        $criteria = $spec->getCriteria();

        $this->assertEquals('((t.a > :t_a) AND (t.a <= :t_a_another)) OR (t.s LIKE :t_s)', $criteria->condition);
        $this->assertEquals(array(':t_a' => 15, ':t_a_another' => 25, ':t_s' => 'some%'), $criteria->params);
    }
}
