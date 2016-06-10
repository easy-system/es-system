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

use Es\System\SystemConfig;

class SystemConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInitialConfig()
    {
        $config = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $systemConfig = new SystemConfig($config);
        $this->assertSame($config, $systemConfig->getInitialConfig());
    }

    public function testGetInitialConfigHash()
    {
        $config = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $hash         = md5(serialize($config));
        $systemConfig = new SystemConfig($config);
        $this->assertSame($hash, $systemConfig->getInitialConfigHash());
    }
}
