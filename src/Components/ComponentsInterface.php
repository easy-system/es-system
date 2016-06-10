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

use Countable;
use Es\Events\EventsInterface;
use Es\Services\ServicesInterface;
use Es\System\ConfigInterface;
use Iterator;

/**
 * The representation of the system components collection.
 */
interface ComponentsInterface extends Countable, Iterator
{
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
    public function register($class);

    /**
     * Whether has the component instance?
     *
     * @param string $class The class of component
     *
     * @return bool Returns true on success, false otherwise
     */
    public function has($class);

    /**
     * Gets the instance of component.
     *
     * @param string $class The class of component
     *
     * @throws \InvalidArgumentException If specified compoent is not registered
     *
     * @return \Es\Component\ComponentInterface The instance of component
     */
    public function get($class);

    /**
     * Whether the components have been initialized?
     *
     * @return bool Returns true on success, false otherwise
     */
    public function isInitialized();

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
    );
}
