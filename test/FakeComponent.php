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

require_once 'FakeListener.php';

use Es\Component\ComponentInterface;
use Es\System\SystemEvent;

class FakeComponent implements ComponentInterface
{
    protected $servicesConfig = [
        'foo' => 'bar',
    ];

    protected $listenersConfig = [
        'FakeListener' => FakeListener::CLASS,
    ];

    protected $eventsConfig = [
        'FakeListener::__invoke' => [
            SystemEvent::INIT,
            'FakeListener',
            '__invoke',
            100,
        ],
    ];

    protected $systemConfig = [
        'foo' => 'bar',
    ];

    public function getVersion()
    {
        return '0.0.0';
    }

    public function getServicesConfig()
    {
        return $this->servicesConfig;
    }

    public function getListenersConfig()
    {
        return $this->listenersConfig;
    }

    public function getEventsConfig()
    {
        return $this->eventsConfig;
    }

    public function getSystemConfig()
    {
        return $this->systemConfig;
    }
}
