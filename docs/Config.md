Usage
=====

After system initialization, the instance of system configuration can be 
retrieved using system services:
```
$config = $services->get('Config');
```

The class of system configuration implements `ArrayAccess` interface. Thus, a
instance of `Es\System\SystemConfig` class can work as a conventional array.

However, the initial system configuration that has been specified at system 
initialization, is protected. The initial configuration can be obtained using 
the method `getInitialConfig()`, but it can not be changed:
```
$systemConfig  = $services->get('Config');
$initialConfig = $systemConfig->getInitialConfig();
```

# Typical usage
```
use Es\Services\ServicesTrait;
use Es\System\ConfigInterface;

class Example
{
    use ServicesTrait;

    protected $config;

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

    public function foo()
    {
        $config = $this->getConfig();
        // ...
    }
}
```
