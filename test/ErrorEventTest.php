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

use Es\System\ErrorEvent;
use Es\System\System;
use Exception;

class ErrorEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $name      = 'Foo';
        $exception = new Exception('Bar');
        $system    = $this
            ->getMockBuilder(System::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $event = new ErrorEvent($name, $exception, $system);

        $this->assertSame($name, $event->getName());
        $this->assertSame($exception, $event->getException());
        $this->assertSame($system, $event->getContext());
    }

    public function invalidErrorTypeDataProvider()
    {
        $errors = [
            null,
            true,
            false,
            100,
            'string',
            [],
            new \stdClass(),
        ];
        $return = [];
        foreach ($errors as $error) {
            $return[] = [$error];
        }

        return $return;
    }

    /**
     * @dataProvider invalidErrorTypeDataProvider
     */
    public function testConstructorRaiseExceptionIfInvalidErrorTypeProvided($error)
    {
        $name   = 'Foo';
        $system = $this
            ->getMockBuilder(System::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $this->setExpectedException('InvalidArgumentException');
        $event = new ErrorEvent($name, $error, $system);
    }
}
