<?php
declare(strict_types=1);

namespace IkonizerCore\Cache;

use IkonizerCore\Cache\Exception\CacheInvalidArgumentException;
use IkonizerCore\Cache\Storage\CacheStorageInterface;
use IkonizerCore\Cache\Storage\NativeCacheStorage;

class CacheFactory
{

    /** @var object */
    protected Object $envConfigurations;

    /**
     * Factory create method which create the cache object and instantiate the storage option
     * We also set a default storage options which is the NativeCacheStorage. So if the second
     * argument within the create method is left to null. Then the default cache storage object
     * will be created and all necessary argument injected within the constructor.
     *
     * @param string|null $cacheIdentifier
     * @param string|null $storage
     * @param array $options
     * @return CacheInterface
     */
    public function create(?string $cacheIdentifier = null, ?string $storage = null, array $options = []): CacheInterface
    {
        $this->envConfigurations = new CacheEnvironmentConfigurations($cacheIdentifier, CACHE_PATH);
        $storageObject = ($storage !== null) ? new $storage($this->envConfigurations, $options) : new NativeCacheStorage($this->envConfigurations, $options);
        if (!$storageObject instanceof CacheStorageInterface) {
            throw new cacheInvalidArgumentException(
                '"' . $storage . '" is not a valid cache storage object.',
                0
            );
        }
        return new Cache($cacheIdentifier, $storageObject, $options);
    }
}