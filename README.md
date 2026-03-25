# ResourceLoader
Load file resources of plugins (PocketMine-MP)

`Loader.php`
```php

class Loader extends PluginBase
{

  public function onLoad() 
  {
    ResourceLoader::init($this, $this->getFile());
  }
}
```
`plugin.yml`
```yml
resources: [
  "config.yml"
]
```

If the file not exists, the `ResourceLoader` throw Exception.
