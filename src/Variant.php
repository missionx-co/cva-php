<?php

namespace MissionX\ClassVariantAuthority;

class Variant
{
    protected array $options = [];

    public function __construct(public string $name) {}

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function add($name, string|array $classes): static
    {
        if (!$this->isValidOptionName($name) || empty($classes)) {
            return $this;
        }

        $this->options[$name] = new Option($name, $classes);

        return $this;
    }

    public function addOption(Option $option)
    {
        if (!$this->isValidOptionName($option->name) || empty($option->classes)) {
            return $this;
        }

        $this->options[$option->name] = $option;

        return $this;
    }

    public function hasOptions(): bool
    {
        return ! empty($this->options);
    }

    public function options(): array
    {
        return $this->options;
    }

    public function resolve(string $option): string|array
    {
        if (! isset($this->options[$option])) {
            return '';
        }

        if (is_array($this->options[$option]->classes)) {
            return implode(' ', $this->options[$option]->classes);
        }

        return $this->options[$option]->classes;
    }

    public static function parse(string $name, array $options): static
    {
        $variant = static::make($name);

        foreach ($options as $option => $classes) {
            if ($classes instanceof Option) {
                $variant->addOption($classes);

                continue;
            }
            $variant->add($option, $classes);
        }

        return $variant;
    }

    protected function isValidOptionName($name)
    {
        return is_bool($name) ||
            (is_int($name) && in_array($name, [0, 1])) ||
            (is_string($name) && !is_numeric($name));
    }
}
