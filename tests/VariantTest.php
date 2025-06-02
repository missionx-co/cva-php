<?php

namespace MissionX\ClassVariantAuthority\Tests;

use MissionX\ClassVariantAuthority\Option;
use MissionX\ClassVariantAuthority\Variant;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class VariantTest extends TestCase
{
    public Variant $variant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->variant = new Variant('color');
    }

    #[Test]
    public function it_adds_option_to_variant_via_name_and_value()
    {
        $this->variant->add('primary', 'bg-blue-100');

        $this->assertTrue($this->variant->hasOptions());
        $this->assertEquals('bg-blue-100', $this->variant->resolve('primary'));
    }

    #[Test]
    public function it_adds_option()
    {
        $this->variant->addOption(new Option('primary', ['bg-blue-100', 'px-2']));

        $this->assertTrue($this->variant->hasOptions());
        $this->assertEquals('bg-blue-100 px-2', $this->variant->resolve('primary'));
    }

    #[Test]
    #[DataProvider('invalidOptionsDataProvider')]
    public function it_does_not_accept_option_because_it_is_in_valid($option)
    {
        if (is_array($option)) {
            $this->variant->add($option['name'], $option['classes']);
        }

        $this->assertFalse($this->variant->hasOptions());
    }

    public static function invalidOptionsDataProvider()
    {
        return [
            'name is integer' => [
                'option' => [
                    'name' => 1,
                    'classes' => 'bg-blue-100',
                ],
            ],
            'name is numeric' => [
                'option' => [
                    'name' => '1',
                    'classes' => 'bg-blue-100',
                ],
            ],
            'classes is empty' => [
                'option' => [
                    'name' => 'primary',
                    'classes' => [],
                ],
            ],
            'option name is integer' => [
                'option' => new Option(1, 'bg-blue-100'),
            ],
            'option name is numeric' => [
                'option' => new Option('1', 'bg-blue-100'),
            ],
            'option classes is empty' => [
                'option' => new Option('primary', ''),
            ],
        ];
    }

    #[Test]
    public function it_parse_options()
    {
        $variant = Variant::parse('color', [
            'primary' => 'bg-blue-100',
            'success' => ['bg-green-100'],
            '1' => ['invalid'],
        ]);

        $this->assertCount(2, $variant->options());
        $this->assertEquals('bg-blue-100', $variant->resolve('primary'));
        $this->assertEquals('bg-green-100', $variant->resolve('success'));
    }
}
