# ResourceLoader
Load file resources of plugins (PocketMine-MP)

How use:
```php

class Loader extends PluginBase
{

  public function onLoad() 
  {
    ResourceLoader::init($this, $this->getFile());
  }
}
```
