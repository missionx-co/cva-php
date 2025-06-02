<?php

namespace MissionX\ClassVariantAuthority;

class Option
{
    public function __construct(public string $name, public string|array $classes) {}
}
