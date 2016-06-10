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

use Es\Events\Event;

/**
 * The Event represents a normal operation of the system.
 */
class SystemEvent extends Event
{
    /**#@+
     * Predefined names of system event
     *
     * @const string
     */
    const INIT      = 'System.Init';
    const BOOTSTRAP = 'System.Bootstrap';
    const ROUTE     = 'System.Route';
    const DISPATCH  = 'System.Dispatch';
    const RENDER    = 'System.Render';
    const FINISH    = 'System.Finish';
    /**#@-*/

    /**
     * The event name.
     *
     * @var string
     */
    protected $name = self::INIT;

    /**
     * Constructor.
     *
     * @param string $name    The name of event
     * @param mixed  $context The event context
     * @param array  $params  The event parameters
     */
    public function __construct($name = null, $context = null, array $params = null)
    {
        parent::__construct($name, $context, $params);
    }
}
