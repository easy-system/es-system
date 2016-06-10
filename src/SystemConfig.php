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

use Es\Container\Configuration\Configuration;

/**
 * The system configuration.
 */
class SystemConfig extends Configuration implements ConfigInterface
{
    /**
     * The initial config.
     *
     * @var array
     */
    protected $initialConfig = [];

    /**
     * The hash of initial configuration.
     *
     * @var string
     */
    protected $initialConfigHash = '';

    /**
     * Constructor.
     *
     * @param array $config Optional; the initial configuration
     */
    public function __construct(array $config = [])
    {
        $this->initialConfig     = $config;
        $this->initialConfigHash = md5(serialize($config));
    }

    /**
     * Gets the initial configuration.
     *
     * @return array The initial configuration
     */
    public function getInitialConfig()
    {
        return $this->initialConfig;
    }

    /**
     * Gets the hash of initial configuration.
     *
     * @return string The hash of initial configuration
     */
    public function getInitialConfigHash()
    {
        return $this->initialConfigHash;
    }
}
