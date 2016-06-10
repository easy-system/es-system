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

/**
 * The interface of system.
 */
interface SystemInterface
{
    /**
     * Initializes system.
     * Returns an instance of the system only once at initialization.
     *
     * @param array $config  Optional; the system configuration
     * @param bool  $devMode Optional; false by default. The system mode
     *
     * @return void|System
     */
    public static function init(array $config = [], $devMode = false);

    /**
     * It is development mode?
     *
     * @return bool Boolean indicator of development mode
     */
    public function isDevMode();

    /**
     * Runs the system.
     */
    public function run();

    /**
     * Gets the components.
     *
     * @return \Es\System\Components\ComponentsInterface The system components
     */
    public function getComponents();

    /**
     * Gets the event.
     *
     * @return SystemEvent The system event
     */
    public function getEvent();
}
