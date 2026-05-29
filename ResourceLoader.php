<?php

/*
 * ██████╗ ██╗███████╗████████╗██╗ ██████╗ 
 * ██╔══██╗██║██╔════╝╚══██╔══╝██║██╔═══██╗
 * ██████╔╝██║█████╗     ██║   ██║██║   ██║
 * ██╔══██╗██║██╔══╝     ██║   ██║██║   ██║
 * ██████╔╝██║███████╗   ██║   ██║╚██████╔╝
 * ╚═════╝ ╚═╝╚══════╝   ╚═╝   ╚═╝ ╚═════╝ 
 * ------------------------------------------
 * @author  Emerson L. (Bietio)
 * @link https://github.com/Bietio
 */

declare(strict_types=1);

namespace Plugin;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
use pocketmine\utils\MainLogger;

/**
 * Trait version test
 */
trait ResourceLoader
{

    /** @var PluginBase */
    private static $plugin;

    /**
     * @param  string $file
     * @return bool
     */
    public static function isFilePhar(string $file): bool
    {
        return (pathinfo($file, PATHINFO_EXTENSION) === 'phar');        
    }

    /** 
     * @param  PluginBase $plugin
     * @throws \Exception
     * @return void
     */
    public function loadResource(PluginBase $plugin)
    {
        self::$plugin = $plugin;
        $file = $this->getFile();

        if(self::isFilePhar($file)) 
        {
            $file = "phar://{$file}";
        } 
        
        $file = new Config($file . 'plugin.yml', Config::YAML);

        $resources = $file->get('resources', false);

        if ($resources === false) 
            return MainLogger::getLogger()->error("Key 'resources' isn't setted in '{$plugin->getName()}'");

        foreach ($resources as $resource):
            $path     = (string) self::getDataFile($resource);
            $dirname  = (string) pathinfo($path, PATHINFO_DIRNAME);

            if (!is_dir($dirname)) 
            {
                mkdir($dirname, 0777, true);
            } 

            if (!is_file($path)) 
            {
                if ($plugin->saveResource($resource) === false) 
                    return MainLogger::getLogger()->error("Not found file '{$resource}' in resources of '{$plugin->getName()}'");
            }
        endforeach;

        MainLogger::getLogger()->info(\pocketmine\utils\TextFormat::GREEN . "Resource files of '{$plugin->getName()}' loaded!");
    }

    /**
     * Return the file with data folder
     * @param ?string $file
     * @throws \Exception
     * @return string
     */
    public static function getDataFile(string $file = null): string
    {
        if (is_null(self::$plugin)) 
            throw new \Exception("Need init the ResourceLoader");

        return self::$plugin->getDataFolder() . $file;
    }
    
    /**
     * @param string $file
     * @param int    $type
     * @param array  $default
     * @param ?bool  $correct
     * @return Config
     */
    public static function newConfig(string $file, int $type, array $default = [], bool &$correct = null)
    {
        return new Config($file, $type, $default, $correct);
    }
}
