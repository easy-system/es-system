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

use Es\System\ConfigInterface;

class ConfigTest //extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $initialConfig = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $config = new SystemConfig($initialConfig);
        $this->assertArrayHasKey('token', $config->getInitialConfig());
        $token = $config->getInitialConfig()['token'];
        $this->assertEquals($token, md5(serialize($initialConfig)));
    }

    public function testGetInitialConfig()
    {
        $initialConfig = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $config = new SystemConfig($initialConfig);
        $this->assertTrue(empty(array_diff($initialConfig, $config->getInitialConfig())));
    }
}
