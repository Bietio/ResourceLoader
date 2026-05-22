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
use Exception;

final class ResourceLoader
{

    const DIRECTORY = "dir";

    const FILE = "file";

    /** @var PluginBase */
    private static $plugin = null;

    /**
     * @param string $file
     * @return bool
     */
    public static function isPhar(string $file): bool
    {
        return (pathinfo($file, PATHINFO_EXTENSION) === 'phar');        
    }
    
    /**
     * @param PluginBase $plugin
     * @param string $file
     * @throws Exception
     * @return void
     */
    public static function init(PluginBase $plugin, string $file)
    {
        self::$plugin = $plugin;

        if(self::isPhar($file)) 
        {
            $file = "phar://{$file}plugin.yml";
        }

        $file = new Config($file, Config::YAML);

        $resources = (array) $file->get('resources', false);
        
        if ($resources === false) 
            throw new Exception("The key \"resources\" not is setted");

        foreach ($resources as $resource):
            $path = self::getDataFile($resource);
            $dirname  = (string) pathinfo($path, PATHINFO_DIRNAME);
            $basename = (string) pathinfo($path, PATHINFO_BASENAME);

            if (!self::isLoaded($dirname, self::DIRECTORY)) 
            {
                mkdir($dirname, 0777, true);
            } 

            if (!self::isLoaded($path, self::FILE) && $basename !== ".") 
            {
                if ($plugin->saveResource($resource) === false) 
                    throw new Exception("File \"{$resource}\" not found in the resources folder");
            }
        endforeach;
    }

    /**
     * Return the file with data folder
     * @param string $file
     * @throws Exception
     * @return string
     */
    public static function getDataFile(string $file = ''): string
    {
        if (is_null(self::$plugin)) 
            throw new Exception("Need init the ResourceLoader");

        return self::$plugin->getDataFolder() . "$file";
    }
    
    /**
     * @param string $file
     * @param int $type
     * @param array $default
     * @param ?bool $correct
     * @return Config
     */
    public static function newConfig(string $file, int $type, array $default = [], bool &$correct = null)
    {
        return new Config($file, $type, $default, $correct);
    }

    /**
     * @param string $resource
     * @param string $type
     * @return bool
     */
    public static function isLoaded(string $resource, string $type = self::DIRECTORY): bool
    {
        if (is_dir($resource) && $type === self::DIRECTORY) 
            return true;

        if (is_file($resource) && $type === self::FILE) 
            return true;

        return false;
    }
}
