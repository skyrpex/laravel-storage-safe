<?php

namespace Pallares\Laravel\StorageSafe;

use Illuminate\Support\Str;
use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * The storage prefix.
     *
     * @var string
     */
    protected $storagePrefix = 'illuminate';

    /**
     * {@inheritdoc}
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        $this->ensureStorageExists();
    }

    /**
     * {@inheritdoc}
     */
    public function storagePath()
    {
        if ($this->storagePath) {
            return $this->storagePath;
        }

        $basename = Str::slug(basename($this->basePath));

        $hash = md5(serialize([
            'user' => function_exists('posix_geteuid') ? posix_geteuid() : null,
            'base_path' => $this->basePath,
        ]));

        return sys_get_temp_dir()."/{$this->storagePrefix}-{$basename}-{$hash}";
    }

    /**
     * {@inheritdoc}
     */
    public function routesAreCached()
    {
        $cachedRoutesPath = $this->getCachedRoutesPath();

        return file_exists($cachedRoutesPath)
            && filemtime(__FILE__) <= filemtime($cachedRoutesPath);
    }

    /**
     * {@inheritdoc}
     */
    public function configurationIsCached()
    {
        $cachedConfigPath = $this->getCachedConfigPath();

        return file_exists($cachedConfigPath)
            && filemtime(__FILE__) <= filemtime($cachedConfigPath);
    }

    /**
    * {@inheritdoc}
     */
    public function getCachedConfigPath()
    {
        return $this->storagePath().'/bootstrap/cache/config.php';
    }

    /**
    * {@inheritdoc}
     */
    public function getCachedRoutesPath()
    {
        return $this->storagePath().'/bootstrap/cache/routes.php';
    }

    /**
    * {@inheritdoc}
     */
    public function getCachedCompilePath()
    {
        return $this->storagePath().'/bootstrap/cache/compiled.php';
    }

    /**
    * {@inheritdoc}
     */
    public function getCachedServicesPath()
    {
        return $this->storagePath().'/bootstrap/cache/services.php';
    }

    /**
     * Ensure that the required storage folders exist.
     */
    protected function ensureStorageExists()
    {
        $paths = [
            '/app/public',
            '/framework/cache',
            '/framework/sessions',
            '/framework/views',
            '/logs',
            '/bootstrap/cache',
        ];
        foreach ($paths as $path) {
            @mkdir($this->storagePath().$path, 0777, true);
        }
    }
}
