<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\System\Listener;

use Es\Cache\Adapter\AbstractCache;
use Es\Cache\CacheFactory;
use Es\Services\ServicesTrait;
use Es\System\ConfigInterface;
use Es\System\SystemEvent;

class ConfigureCacheListener
{
    use ServicesTrait;

    protected $cache;

    protected $config;

    public function setCache(AbstractCache $cache)
    {
        $this->cache = $cache->withNamespace('system');
    }

    public function getCache()
    {
        if (! $this->cache) {
            $services = $this->getServices();
            $cache    = $services->get('Cache');
            $this->setCache($cache);
        }

        return $this->cache;
    }

    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        if (! $this->config) {
            $services = $this->getServices();
            $config   = $services->get('Config');
            $this->setConfig($config);
        }

        return $this->config;
    }

    public function configureFactory(SystemEvent $event)
    {
        $systemConfig  = $this->getConfig();
        $initialConfig = $systemConfig->getInitialConfig();

        if (isset($initialConfig['cache'])) {
            $cacheConfig = (array) $initialConfig['cache'];
            CacheFactory::setConfig($cacheConfig);
        }
    }

    public function configureNamespace(SystemEvent $event)
    {
        $systemConfig = $this->getConfig();

        $cache = $this->getCache();
        if ($cache->get('hash') !== $systemConfig->getInitialConfigHash()) {
            $cache->clearNamespace();
            $cache->set('hash', $systemConfig->getInitialConfigHash());
        }
    }
}
