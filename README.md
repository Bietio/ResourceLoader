# ResourceLoader
Load file resources of plugins (PocketMine-MP)

`Loader.php`
```php

class Loader extends PluginBase
{

  use ResourceLoader;

  /**
   * @return void
   */
  public function onLoad() 
  {
    ResourceLoader::loadResource($this);
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
