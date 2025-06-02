<?php

namespace MissionX\ClassVariantAuthority;

use MissionX\ClassVariantAuthority\Exceptions\EmptyVariantsException;
use TailwindMerge\TailwindMerge;

class ClassVariantAuthority
{
    public Config $config;

    public function __construct(
        public array|string $base = '',
        /**
         * @var array<int, \MissionX\ClassVariantAuthority\Variant>
         */
        public array $variants = [],
        /**
         * @var array<int, \MissionX\ClassVariantAuthority\CompoundVariants>
         */
        public array $compoundVariants = [],
        public array $defaultVariants = [],
    ) {}

    public static function make(): static
    {
        return new static;
    }

    public static function withConfig(Config $config)
    {
        return (new static)
            ->setConfig($config);
    }

    public function setConfig(Config $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function setBase(string|array $base): static
    {
        $this->base = $base;

        return $this;
    }

    public function addVariant(Variant $variant): static
    {
        if (! $variant->hasOptions()) {
            return $this;
        }

        $this->variants[$variant->name] = $variant;

        return $this;
    }

    public function addCompoundVariants(CompoundVariants $compoundVariants): static
    {
        if (empty($compoundVariants->conditions())) {
            return $this;
        }

        $this->compoundVariants[$compoundVariants->hash()] = $compoundVariants;

        return $this;
    }

    public function setDefaultVariants(array $defaultVariants): static
    {
        $this->defaultVariants = $defaultVariants;

        return $this;
    }

    public function resolve(array $props = []): string
    {
        $props = array_merge($this->defaultVariants, $props);

        $classes = [
            $this->base,
        ];

        foreach ($this->variants as $name => $variant) {
            if (! isset($props[$name])) {
                continue;
            }
            $classes[] = $variant->resolve($props[$name]);
        }

        foreach ($this->compoundVariants as $compoundVariant) {
            if (! $compoundVariant->match($props)) {
                continue;
            }

            $classes[] = $compoundVariant->classes();
        }

        return $this->merger()->merge($this->flatten($classes), $props['class'] ?? '');
    }

    public function __invoke(array $props = [])
    {
        return $this->resolve($props);
    }

    public function merger(): TailwindMerge
    {
        $factory = TailwindMerge::factory();
        if (! isset($this->config)) {
            return $factory->make();
        }

        if (isset($this->config->tailwindMergeConfig)) {
            $factory->withConfiguration($this->config->tailwindMergeConfig);
        }

        if (isset($this->config->cache)) {
            $factory->withCache($this->config->cache);
        }

        return $factory->make();
    }

    public static function parse(array $variants, ?Config $config = null): static
    {
        if (empty($variants['variants'])) {
            throw new EmptyVariantsException;
        }

        $instance = (new static($variants['base'] ?? ''))
            ->parseVariantsFromArray($variants['variants'])
            ->parseCompoundVariants($variants['compound_variants'] ?? [])
            ->setDefaultVariants($variants['default_variants'] ?? []);

        if ($config) {
            $instance->withConfig($config);
        }

        return $instance;
    }

    protected function parseVariantsFromArray(array $variants)
    {
        foreach ($variants as $name => $options) {
            $this->addVariant(Variant::parse($name, $options));
        }

        return $this;
    }

    public function parseCompoundVariants(array $groups): static
    {
        if (empty($groups)) {
            return $this;
        }

        foreach ($groups as $compoundVariants) {
            $this->addCompoundVariants(CompoundVariants::parse($compoundVariants));
        }

        return $this;
    }

    protected function flatten(array $array)
    {
        $return = [];
        array_walk_recursive($array, function ($a) use (&$return) {
            $return[] = $a;
        });

        return $return;
    }
}
