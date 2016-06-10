Usage
=====
Specify in the startup configuration the list of components that need to be 
initialized:
```
$config = [
    'components' => [
        'Es\Component\Component',
        'Es\Exception\Component',
        'Es\Container\Component',
        'Es\Services\Component',
        'Es\Events\Component',
        'Es\Cache\Component',
        'Es\System\Component',
        // ....
    ],
];
```

In your index file `index.php` perform the system initialization and run system:
```
$developmentMode = true;

$system = \Es\System\System::init($config, $developmentMode);
$system->run();
```
> Note: If you do not specify `true` as a second parameter of initialization, 
the system will be run in production mode.
