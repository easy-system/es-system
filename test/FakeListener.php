<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\System\Test;

use Es\System\SystemEvent;

class FakeListener
{
    public function __invoke(SystemEvent $event)
    {
        $event->setResult(SystemEvent::INIT, true);
    }
}
