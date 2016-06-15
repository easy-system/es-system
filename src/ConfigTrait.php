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

use Es\Services\Provider;

/**
 * The accessors of system configuration.
 */
trait ConfigTrait
{
    /**
     * Sets the system configuration.
     *
     * @param ConfigInterface $config The system configuration
     */
    public function setConfig(ConfigInterface $config)
    {
        Provider::getServices()->set('Config', $config);
    }

    /**
     * Gets the system configuration.
     *
     * @return ConfigInterface The system configuration
     */
    public function getConfig()
    {
        return Provider::getServices()->get('Config');
    }
}
