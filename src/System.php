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

use Error;
use Es\Events\Events;
use Es\Events\Listeners;
use Es\Services\ServicesTrait;
use Es\System\Components\Components;
use Exception;

/**
 * The system.
 */
class System implements SystemInterface
{
    use ServicesTrait;

    /**
     * The instance of System.
     *
     * @var null|System
     */
    protected static $instance;

    /**
     * The system components.
     *
     * @var \Es\System\Components\Components
     */
    protected $components;

    /**
     * The system event.
     *
     * @var SystemEvent
     */
    protected $event;

    /**
     * Boolean  indicator of development mode.
     *
     * @var int
     */
    protected $devMode = false;

    /**
     * Initializes system.
     * Returns an instance of the system only once at initialization.
     *
     * @param array $config  Optional; the system configuration
     * @param bool  $devMode Optional; false by default. The system mode
     *
     * @return self|void Returns the instance of System only once
     */
    public static function init(array $config = [], $devMode = false)
    {
        if (static::$instance instanceof static) {
            return;
        }

        $system = static::$instance = new static();

        $system->devMode = (bool) $devMode;

        $services = $system->getServices();
        $services->set('System', $system);

        $items = [];
        if (isset($config['components'])) {
            $items = (array) $config['components'];
        }
        $components = $system->getComponents();
        foreach ($items as $index => $item) {
            $config['components'][$index] = $components->register($item);
        }

        $systemConfig = new SystemConfig($config);
        $services->set('Config', $systemConfig);

        $listeners = new Listeners();
        $services->set('Listeners', $listeners);

        $events = new Events();
        $services->set('Events', $events);

        $components->init($services, $listeners, $events, $systemConfig);
        $events->trigger($system->getEvent());

        return $system;
    }

    /**
     * It is development mode?
     *
     * @return bool Boolean indicator of development mode
     */
    public function isDevMode()
    {
        return $this->devMode;
    }

    /**
     * Runs system.
     */
    public function run()
    {
        $services = $this->getServices();
        $events   = $services->get('Events');

        $event = $this->getEvent();
        $event->setContext($this);

        $course = [
            SystemEvent::BOOTSTRAP,
            SystemEvent::ROUTE,
            SystemEvent::DISPATCH,
            SystemEvent::RENDER,
        ];

        try {
            foreach ($course as $eventName) {
                if ($event->getResult(SystemEvent::FINISH)) {
                    break;
                }
                $events->trigger($event($eventName));
            }

            $events->trigger($event(SystemEvent::FINISH));
        } catch (Error $ex) {
            $error = new ErrorEvent(ErrorEvent::FATAL_ERROR, $ex, $this);
            $events->trigger($error);
        } catch (Exception $ex) {
            $error = new ErrorEvent(ErrorEvent::FATAL_ERROR, $ex, $this);
            $events->trigger($error);
        }
    }

    /**
     * Gets the components.
     *
     * @return \Es\System\Components\Components The system components
     */
    public function getComponents()
    {
        if (! $this->components) {
            $this->components = new Components();
        }

        return $this->components;
    }

    /**
     * Gets the event.
     *
     * @return SystemEvent The system event
     */
    public function getEvent()
    {
        if (! $this->event) {
            $this->event = new SystemEvent();
        }

        return $this->event;
    }

    /**
     * Constructor.
     */
    protected function __construct()
    {
    }

    /**
     * Creating a copy of an object.
     *
     * @codeCoverageIgnore
     */
    private function __clone()
    {
    }
}
