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
 * The accessors of System.
 */
trait SystemTrait
{
    /**
     * Sets the system.
     *
     * @param SystemInterface $system The system
     */
    public function setSystem(SystemInterface $system)
    {
        Provider::getServices()->set('System', $system);
    }

    /**
     * Gets the system.
     *
     * @return SystemInterface The system
     */
    public function getSystem()
    {
        return Provider::getServices()->get('System');
    }
}
