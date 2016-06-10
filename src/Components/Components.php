<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\System\Components;

use Es\Component\ComponentInterface;
use Es\Container\AbstractContainer;
use Es\Container\Countable\CountableTrait;
use Es\Container\Iterator\IteratorTrait;
use Es\Events\EventsInterface;
use Es\Services\ServicesInterface;
use Es\System\ConfigInterface;
use InvalidArgumentException;
use RuntimeException;

/**
 * The collection of system components.
 */
class Components extends AbstractContainer implements ComponentsInterface
{
    use CountableTrait, IteratorTrait;

    /**
     * Whether the components have been initialized?
     *
     * @var bool
     */
    protected $initialized = false;

    /**
     * Register the component.
     *
     * @param string $class The class of component
     *
     * @throws \InvalidArgumentException If the specified class not implements
     *                                   the Es\Component\ComponentInterface
     * @throws \RuntimeException
     *
     * - If If the components have already been initialized
     * - If the specified class of component does not exists
     *
     * @return string The hash of component state
     */
    public function register($class)
    {
        if ($this->initialized) {
            throw new RuntimeException(
                'The components have already been initialized.'
            );
        }
        if (! class_exists($class)) {
            throw new RuntimeException(sprintf(
                'The component "%s" not exists.',
                $class
            ));
        }
        $component = new $class();
        if (! $component instanceof ComponentInterface) {
            throw new InvalidArgumentException(sprintf(
                'The component "%s" must implement "%s".',
                $class,
                ComponentInterface::CLASS
            ));
        }

        $this->container[$class] = $component;

        return md5($class . $component->getVersion());
    }

    /**
     * Whether has the component instance?
     *
     * @param string $class The class of component
     *
     * @return bool Returns true on success, false otherwise
     */
    public function has($class)
    {
        return isset($this->container[$class]);
    }

    /**
     * Gets the instance of component.
     *
     * @param string $class The class of component
     *
     * @throws \InvalidArgumentException If specified compoent is not registered
     *
     * @return \Es\Component\ComponentInterface The instance of component
     */
    public function get($class)
    {
        $class = (string) $class;

        if (! isset($this->container[$class])) {
            throw new InvalidArgumentException(sprintf(
                'The component of class "%s" is not registered.',
                $class
            ));
        }

        return $this->container[$class];
    }

    /**
     * Whether the components have been initialized?
     *
     * @return bool Returns true on success, false otherwise
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * Initializes components.
     *
     * @param \Es\Services\ServicesInterface $services The services
     * @param \Es\Events\EventsInterface     $events   The events
     * @param \Es\System\ConfigInterface     $config   The system configuration
     *
     * @throws \RuntimeException If the components have already been initialized
     */
    public function init(
        ServicesInterface $services,
        EventsInterface $events,
        ConfigInterface $config
    ) {
        if ($this->initialized) {
            throw new RuntimeException(
                'The components have already been initialized.'
            );
        }
        $this->initialized = true;

        foreach ($this->container as $component) {
            if (method_exists($component, 'getServicesConfig')) {
                $services->add($component->getServicesConfig());
            }
            if (method_exists($component, 'getEventsConfig')) {
                foreach ($component->getEventsConfig() as $item) {
                    call_user_func_array([$events, 'attach'], $item);
                }
            }
            if (method_exists($component, 'getSystemConfig')) {
                $config->merge($component->getSystemConfig());
            }
        }
    }
}
