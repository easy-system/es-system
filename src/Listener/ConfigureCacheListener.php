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

/**
 * Configures cache.
 */
class ConfigureCacheListener
{
    use ServicesTrait;

    /**
     * The adapter of cache.
     *
     * @var \Es\Cache\Adapter\AbstractCache
     */
    protected $cache;

    /**
     * The system configuration.
     *
     * @var \Es\System\ConfigInterface
     */
    protected $config;

    /**
     * Sets the cache adapter.
     *
     * @param \Es\Cache\Adapter\AbstractCache $cache The cache adapter
     */
    public function setCache(AbstractCache $cache)
    {
        $this->cache = $cache->withNamespace('system');
    }

    /**
     * Gets the cache adapter.
     *
     * @param \Es\Cache\Adapter\AbstractCache The cache adapter
     */
    public function getCache()
    {
        if (! $this->cache) {
            $services = $this->getServices();
            $cache    = $services->get('Cache');
            $this->setCache($cache);
        }

        return $this->cache;
    }

    /**
     * Sets the system configuration.
     *
     * @param \Es\System\ConfigInterface $config The system configuration
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Gets the system configuration.
     *
     * @param \Es\System\ConfigInterface The system configuration
     */
    public function getConfig()
    {
        if (! $this->config) {
            $services = $this->getServices();
            $config   = $services->get('Config');
            $this->setConfig($config);
        }

        return $this->config;
    }

    /**
     * Configures the cache factory.
     *
     * @param \Es\System\SystemEvent $event The system event
     */
    public function configureFactory(SystemEvent $event)
    {
        $systemConfig  = $this->getConfig();
        $initialConfig = $systemConfig->getInitialConfig();

        if (isset($initialConfig['cache'])) {
            $cacheConfig = (array) $initialConfig['cache'];
            CacheFactory::setConfig($cacheConfig);
        }
    }

    /**
     * Configures the namespace registered in the cache for system.
     *
     * @param \Es\System\SystemEvent $event The system event
     */
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
