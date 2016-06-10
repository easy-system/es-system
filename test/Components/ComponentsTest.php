<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\System\Test\Components;

use Es\Events\Events;
use Es\Services\Provider;
use Es\Services\Services;
use Es\System\Components\Components;
use Es\System\SystemConfig;
use Es\System\SystemEvent;
use Es\System\Test\FakeComponent;
use Es\System\Test\FakeListener;

class ComponentsTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $testDir = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        require_once $testDir . 'FakeComponent.php';
    }

    public function testRegisterRaiseExceptionIfComponentsAlreadyInitialized()
    {
        $services = new Services();
        $events   = new Events();
        $config   = new SystemConfig();

        $components = new Components();
        $components->init($services, $events, $config);
        $this->setExpectedException('RuntimeException');
        $components->register(FakeComponent::CLASS);
    }

    public function testRegisterRaiseExceptionIfComponentClassNotExists()
    {
        $components = new Components();
        $this->setExpectedException('RuntimeException');
        $components->register('Non\Existent\Component');
    }

    public function testRegisterRaiseExceptionIfComponentNotImplementsComponentInterface()
    {
        $components = new Components();
        $this->setExpectedException('InvalidArgumentException');
        $components->register('stdClass');
    }

    public function testRegisterReturnsHashOfComponentState()
    {
        $components = new Components();
        $hash       = $components->register(FakeComponent::CLASS);
        $this->assertInternalType('string', $hash);
        $this->assertSame(32, strlen($hash));
    }

    public function testRegisterRegistersComponent()
    {
        $components = new Components();
        $components->register(FakeComponent::CLASS);

        $components->rewind();
        $component = $components->current();
        $this->assertInstanceOf(FakeComponent::CLASS, $component);
    }

    public function testHas()
    {
        $components = new Components();
        $this->assertFalse($components->has(FakeComponent::CLASS));

        $components->register(FakeComponent::CLASS);
        $this->assertTrue($components->has(FakeComponent::CLASS));
    }

    public function testGetRaiseExceptionIfComponentIsNotRegistered()
    {
        $components = new Components();
        $this->setExpectedException('InvalidArgumentException');
        $components->get('Foo');
    }

    public function testGetOnSuccess()
    {
        $components = new Components();
        $components->register(FakeComponent::CLASS);
        $component = $components->get(FakeComponent::CLASS);
        $this->assertInstanceOf(FakeComponent::CLASS, $component);
    }

    public function testIsInitialized()
    {
        $services = new Services();
        $events   = new Events();
        $config   = new SystemConfig();

        $components = new Components();
        $this->assertFalse($components->isInitialized());

        $components->init($services, $events, $config);
        $this->assertTrue($components->isInitialized());
    }

    public function testInitRaiseExceptionIfComponentsAlreadyInitialized()
    {
        $services = new Services();
        $events   = new Events();
        $config   = new SystemConfig();

        $components = new Components();
        $components->init($services, $events, $config);

        $this->setExpectedException('RuntimeException');
        $components->init($services, $events, $config);
    }

    public function testInitOnSuccess()
    {
        $events   = new Events();
        $config   = new SystemConfig();
        $services = new Services();
        $services->set('FakeListener', FakeListener::CLASS);
        Provider::setServices($services);

        $components = new Components();
        $components->register(FakeComponent::CLASS);
        $components->init($services, $events, $config);

        $component = $components->get(FakeComponent::CLASS);

        $this->assertSame($component->getServicesConfig(), $services->getRegistry());
        $this->assertSame($component->getSystemConfig(), $config->toArray());

        $event = new SystemEvent();
        $events->trigger($event);
        $this->assertTrue($event->getResult(SystemEvent::INIT));
    }
}
