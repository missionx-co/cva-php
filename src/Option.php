<?php

namespace MissionX\ClassVariantAuthority;

class Option
{
    public function __construct(public $name, public string|array $classes) {}
}
