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
use Es\Events\AbstractEvent;
use Exception;
use InvalidArgumentException;

/**
 * The event of system error.
 */
class ErrorEvent extends AbstractEvent
{
    /**
     * Predefined name of the fatal system error.
     *
     * @const string
     */
    const FATAL_ERROR = 'System.Fail';

    /**
     * The exception or the error.
     *
     * @var \Exception|\Error
     */
    protected $throwable;

    /**
     * Constructor.
     *
     * @param string            $name    The event name
     * @param \Exception|\Error $e       The exception or the error
     * @param System            $context The instance of system as context
     */
    public function __construct($name, $e, System $context)
    {
        if (! $e instanceof Exception && ! $e instanceof Error) {
            throw new InvalidArgumentException(sprintf(
                'Invalid error type provided; must be an instance of '
                . '"Exception" or "Error", "%s" received.',
                gettype($e)
            ));
        }
        $this->throwable = $e;
        $this->context   = $context;
        $this->name      = (string) $name;
    }

    /**
     * Gets the exception or the error.
     *
     * @return \Exception|\Error The exception or the error
     */
    public function getException()
    {
        return $this->throwable;
    }
}
