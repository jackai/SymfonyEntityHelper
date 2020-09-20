<?php

namespace Jackai\EntityHelper;

use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ClearEntityHelperCache implements CacheClearerInterface
{
    public function clear($cacheDirectory)
    {
        $cache = new FilesystemAdapter('app.jackai.entity_helper');
        $cache->clear();
    }
}
