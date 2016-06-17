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

use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\System\System;

class SystemTraitTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function setUp()
    {
        require_once 'SystemTraitTemplate.php';
        require_once 'SystemTestHelper.php';

        SystemTestHelper::resetSystem();
    }

    public function testSetSystem()
    {
        $services = new Services();
        $this->setServices($services);

        $system   = System::init();
        $template = new SystemTraitTemplate();
        $template->setSystem($system);
        $this->assertSame($system, $services->get('System'));
    }

    public function testGetSystem()
    {
        $system   = System::init();
        $services = new Services();
        $services->set('System', $system);

        $this->setServices($services);
        $template = new SystemTraitTemplate();
        $this->assertSame($system, $template->getSystem());
    }
}
