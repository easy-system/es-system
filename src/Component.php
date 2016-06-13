<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\System;

use Es\Component\ComponentInterface;

/**
 * The system component.
 */
class Component implements ComponentInterface
{
    /**
     * The configuration of listeners.
     *
     * @var array
     */
    protected $listenersConfig = [
        'Es.System.Listener.ConfigureCacheListener' => 'Es\System\Listener\ConfigureCacheListener',
    ];

    /**
     * The configuration of events.
     *
     * @var array
     */
    protected $eventsConfig = [
        'ConfigureCacheListener::configureFactory' => [
            SystemEvent::INIT,
            'Es.System.Listener.ConfigureCacheListener',
            'configureFactory',
            9000,
        ],
        'ConfigureCacheListener::configureNamespace' => [
            SystemEvent::INIT,
            'Es.System.Listener.ConfigureCacheListener',
            'configureNamespace',
            8000,
        ],
    ];

    /**
     * The current version of component.
     *
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Gets the current version of component.
     *
     * @return string The version of component
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Gets the configuration of listeners.
     *
     * @return array The configuration of listeners
     */
    public function getListenersConfig()
    {
        return $this->listenersConfig;
    }

    /**
     * Gets the configuration of events.
     *
     * @return array The configuration of events
     */
    public function getEventsConfig()
    {
        return $this->eventsConfig;
    }
}
