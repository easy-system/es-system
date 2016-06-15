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

use Es\Services\Provider;
use Es\Services\Services;
use Es\System\SystemConfig;

class ConfigTraitTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once 'ConfigTraitTemplate.php';
    }

    public function testSetConfig()
    {
        $config   = new SystemConfig();
        $template = new ConfigTraitTemplate();
        $template->setConfig($config);
        $this->assertSame($config, $template->getConfig());
    }

    public function testGetConfig()
    {
        $config   = new SystemConfig();
        $services = new Services();
        $services->set('Config', $config);

        Provider::setServices($services);
        $template = new ConfigTraitTemplate();
        $this->assertSame($config, $template->getConfig());
    }
}
