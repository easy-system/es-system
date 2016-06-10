<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\System\Test;

use Es\System\SystemEvent;

class SystemEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $name    = 'Foo';
        $context = 'Bar';
        $params  = ['baz' => 'bat'];

        $event = new SystemEvent($name, $context, $params);

        $this->assertSame($name, $event->getName());
        $this->assertSame($context, $event->getContext());
        $this->assertSame($params, $event->getParams());
    }
}
