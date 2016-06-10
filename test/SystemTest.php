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

use Error;
use Es\Events\Events;
use Es\Events\EventsInterface;
use Es\Services\Provider;
use Es\Services\Services;
use Es\System\Components\ComponentsInterface;
use Es\System\ErrorEvent;
use Es\System\System;
use Es\System\SystemConfig;
use Es\System\SystemEvent;
use Exception;
use ReflectionClass;

class SystemTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (! class_exists('Error', false)) {
            require_once 'Error.php';
        }
        require_once 'SystemTestHelper.php';
        require_once 'FakeComponent.php';
    }

    public function testConstructor()
    {
        $reflection = new ReflectionClass(System::CLASS);
        $this->assertFalse($reflection->isInstantiable());
    }

    public function testCloneable()
    {
        $reflection = new ReflectionClass(System::CLASS);
        $this->assertFalse($reflection->isCloneable());
    }

    public function testGetComponents()
    {
        SystemTestHelper::resetSystem();

        $system = System::init();
        $this->assertInstanceOf(ComponentsInterface::CLASS, $system->getComponents());
    }

    public function testGetEvent()
    {
        SystemTestHelper::resetSystem();

        $system = System::init();
        $this->assertInstanceOf(SystemEvent::CLASS, $system->getEvent());
    }

    public function testInitReturnsTheSystemInstance()
    {
        SystemTestHelper::resetSystem();
        $system = System::init();
        $this->assertInstanceOf(System::CLASS, $system);
    }

    public function testInitReturnsTheSystemInstanceOnlyOnce()
    {
        SystemTestHelper::resetSystem();
        $system = System::init();
        $this->assertInstanceOf(System::CLASS, $system);

        $this->assertNull(System::init());
    }

    public function testInitCreateConfig()
    {
        SystemTestHelper::resetSystem();
        Provider::setServices(new Services());

        $config = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        System::init($config);
        $services = Provider::getServices();

        $this->assertTrue($services->has('Config'));
        $systemConfig = $services->get('Config');

        $this->assertInstanceOf(SystemConfig::CLASS, $systemConfig);
        $this->assertSame($config, $systemConfig->getInitialConfig());
    }

    public function testInitCreateEvents()
    {
        SystemTestHelper::resetSystem();
        Provider::setServices(new Services());

        System::init();
        $services = Provider::getServices();
        $this->assertTrue($services->has('Events'));
        $this->assertInstanceOf(EventsInterface::CLASS, $services->get('Events'));
    }

    public function testInitSetDevMode()
    {
        SystemTestHelper::resetSystem();
        Provider::setServices(new Services());

        $system = System::init([], false);
        $this->assertFalse($system->isDevMode());
        //
        SystemTestHelper::resetSystem();
        $system = System::init([], true);
        $this->assertTrue($system->isDevMode());
    }

    public function testInitRegisterComponents()
    {
        $config = [
            'components' => [
                FakeComponent::CLASS,
            ],
        ];
        SystemTestHelper::resetSystem();
        $system     = System::init($config);
        $components = $system->getComponents();
        $this->assertTrue($components->has(FakeComponent::CLASS));
    }

    public function testInitInitializeComponents()
    {
        SystemTestHelper::resetSystem();
        $system     = System::init();
        $components = $system->getComponents();
        $this->assertTrue($components->isInitialized());
    }

    public function testInitTriggerInitEvent()
    {
        $config = [
            'components' => [
                FakeComponent::CLASS,
            ],
        ];
        SystemTestHelper::resetSystem();
        $system = System::init($config);
        $event  = $system->getEvent();
        $this->assertTrue($event->getResult(SystemEvent::INIT));
    }

    public function testRunCourse()
    {
        SystemTestHelper::resetSystem();
        Provider::setServices(new Services());

        $system   = System::init();
        $services = Provider::getServices();
        $events   = $this->getMock(Events::CLASS);
        $services->set('Events', $events);
        $course = [
            SystemEvent::BOOTSTRAP,
            SystemEvent::ROUTE,
            SystemEvent::DISPATCH,
            SystemEvent::RENDER,
            SystemEvent::FINISH,
        ];
        $events
            ->expects($this->atLeastOnce())
            ->method('trigger')
            ->will($this->returnCallback(
                function ($event) use (&$course) {
                    $this->assertTrue($event->getName() == array_shift($course));
                }
            ));

        $system->run();
    }

    public function testTriggerErrorBreakCource()
    {
        SystemTestHelper::resetSystem();
        Provider::setServices(new Services());

        $system   = System::init();
        $services = Provider::getServices();
        $events   = $this->getMock(Events::CLASS);
        $services->set('Events', $events);

        $course = [
            SystemEvent::BOOTSTRAP,
            SystemEvent::ROUTE,
            //... SystemEvent::DISPATCH
            //... SystemEvent::RENDER
            ErrorEvent::FATAL_ERROR,
            SystemEvent::FINISH,
        ];

        $events
            ->expects($this->atLeastOnce())
            ->method('trigger')
            ->will($this->returnCallback(
                function ($event) use (&$course) {
                    if ($event->getName() == SystemEvent::DISPATCH) {
                        throw new Error('Error');
                    }
                    $this->assertTrue($event->getName() == array_shift($course));
                }
            ));

        $system->run();
    }

    public function testTriggerExceptionBreakCource()
    {
        SystemTestHelper::resetSystem();
        Provider::setServices(new Services());

        $system   = System::init();
        $services = Provider::getServices();
        $events   = $this->getMock(Events::CLASS);
        $services->set('Events', $events);

        $course = [
            SystemEvent::BOOTSTRAP,
            SystemEvent::ROUTE,
            //... SystemEvent::DISPATCH
            //... SystemEvent::RENDER
            ErrorEvent::FATAL_ERROR,
            SystemEvent::FINISH,
        ];

        $events
            ->expects($this->atLeastOnce())
            ->method('trigger')
            ->will($this->returnCallback(
                function ($event) use (&$course) {
                    if ($event->getName() == SystemEvent::DISPATCH) {
                        throw new Exception('Error');
                    }
                    $this->assertTrue($event->getName() == array_shift($course));
                }
            ));

        $system->run();
    }

    public function testSetsFinishResultBreakCource()
    {
        SystemTestHelper::resetSystem();
        Provider::setServices(new Services());

        $system   = System::init();
        $services = Provider::getServices();
        $events   = $this->getMock(Events::CLASS);
        $services->set('Events', $events);

        $course = [
            SystemEvent::BOOTSTRAP,
            SystemEvent::ROUTE,
            //... SystemEvent::DISPATCH
            //... SystemEvent::RENDER
            SystemEvent::FINISH,
        ];

        $events
            ->expects($this->atLeastOnce())
            ->method('trigger')
            ->will($this->returnCallback(
                function ($event) use (&$course) {
                    if ($event->getName() == SystemEvent::ROUTE) {
                        $event->setResult(SystemEvent::FINISH, 'foo');
                    }
                    $this->assertTrue($event->getName() == array_shift($course));
                }
            ));

        $system->run();
    }
}
