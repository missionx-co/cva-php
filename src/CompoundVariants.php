<?php

namespace MissionX\ClassVariantAuthority;

class CompoundVariants
{
    protected array $conditions = [];

    protected string|array $classes = [];

    public static function make(): static
    {
        return new static;
    }

    public function addCondition(string $option, string $value): static
    {
        $this->conditions[$option] = $value;

        return $this;
    }

    public function setClasses(string $classes): static
    {
        $this->classes = $classes;

        return $this;
    }

    public function conditions(): array
    {
        return $this->conditions;
    }

    public function classes(): string|array
    {
        if (is_array($this->classes)) {
            return implode(' ', $this->classes);
        }

        return $this->classes;
    }

    public function hash(): string
    {
        asort($this->conditions);

        return implode(
            '|',
            array_keys(
                $this->conditions
            )
        );
    }

    public function match(array $props): bool
    {
        if (empty($this->conditions)) {
            return false;
        }

        $targetProps = array_intersect_key(
            $props,
            array_flip(
                array_keys($this->conditions)
            )
        );

        return $targetProps == $this->conditions;
    }

    public static function parse(array $group)
    {
        $instance = static::make();

        foreach ($group as $name => $value) {
            if ($name == 'class') {
                $instance->setClasses($value);

                continue;
            }

            $instance->addCondition($name, $value);
        }

        return $instance;
    }
}
