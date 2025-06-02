<?php

namespace MissionX\ClassVariantAuthority;

use Psr\SimpleCache\CacheInterface;

class Config
{
    public array $tailwindMergeConfig;

    public CacheInterface $cache;
}
