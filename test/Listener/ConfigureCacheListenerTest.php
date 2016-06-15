<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\System\Test\Listener;

use Es\Cache\Adapter\FileCache;
use Es\Cache\CacheFactory;
use Es\Services\Services;
use Es\System\Listener\ConfigureCacheListener;
use Es\System\SystemConfig;
use Es\System\SystemEvent;
use ReflectionProperty;

class ConfigureCacheListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testSetCache()
    {
        $listener = new ConfigureCacheListener();
        $listener->setCache(new FileCache());

        $reflection = new ReflectionProperty($listener, 'cache');
        $reflection->setAccessible(true);
        $cache = $reflection->getValue($listener);

        $this->assertInstanceOf(FileCache::CLASS, $cache);
        $this->assertSame('system', $cache->getNamespace());
    }

    public function testGetCache()
    {
        $services = new Services();
        $cache    = new FileCache();
        $services->set('Cache', $cache);

        $listener = $this->getMock(ConfigureCacheListener::CLASS, ['setCache']);
        $listener->setServices($services);

        $listener
            ->expects($this->once())
            ->method('setCache')
            ->with($this->identicalTo($cache));

        $listener->getCache();
    }

    public function testConfigureFactory()
    {
        $config = [
            'cache' => [
                'defaults' => [
                    'adapter' => 'foo',
                    'options' => [
                        'enabled' => false,
                    ],
                ],
                'adapters' => [
                    'foo' => [
                        'class'   => FileCache::CLASS,
                        'options' => [
                            'basedir'           => 'bar',
                            'hashing_algorithm' => 'crc32',
                        ],
                    ],
                ],
            ],
        ];
        $systemConfig = new SystemConfig($config);
        $listener     = new ConfigureCacheListener();
        $listener->setConfig($systemConfig);
        $listener->configureFactory(new SystemEvent());
        $this->assertSame($config['cache'], CacheFactory::getConfig());
    }

    public function testConfigureNamespace()
    {
        $systemConfig = new SystemConfig();
        $configHash   = $systemConfig->getInitialConfigHash();

        $listener = $this->getMock(ConfigureCacheListener::CLASS, ['getCache']);
        $listener->setConfig($systemConfig);

        $cache = $this->getMock(FileCache::CLASS);
        $listener
            ->expects($this->once())
            ->method('getCache')
            ->will($this->returnValue($cache));

        $cache
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo('hash'))
            ->will($this->returnValue('foo'));

        $cache
            ->expects($this->once())
            ->method('clearNamespace');

        $cache
            ->expects($this->once())
            ->method('set')
            ->with(
                $this->identicalTo('hash'),
                $this->identicalTo($configHash)
            );

        $listener->configureNamespace(new SystemEvent());
    }
}
