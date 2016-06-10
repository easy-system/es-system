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

use Es\Container\Configuration\ConfigurationInterface;

/**
 * The interface of system configuration.
 */
interface ConfigInterface extends ConfigurationInterface
{
    /**
     * Gets the initial configuration.
     *
     * @return array The initial configuration
     */
    public function getInitialConfig();

    /**
     * Gets the hash of initial configuration.
     *
     * @return string The hash of initial configuration
     */
    public function getInitialConfigHash();
}
