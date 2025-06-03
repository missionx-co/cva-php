<?php

namespace MissionX\ClassVariantAuthority\Tests;

use MissionX\ClassVariantAuthority\ClassVariantAuthority;
use MissionX\ClassVariantAuthority\CompoundVariants;
use MissionX\ClassVariantAuthority\Option;
use MissionX\ClassVariantAuthority\Variant;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ClassVariantAuthorityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function it_parse_array()
    {
        $cva = ClassVariantAuthority::parse([
            'base' => 'text-sm px-3 py-2',
            'variants' => [
                'color' => [
                    new Option('primary', 'bg-blue-100'),
                ],
                'size' => [
                    'sm' => 'px-2 py-1',
                    'lg' => 'px-4 py-3',
                ],
            ],
            'compound_variants' => [
                [
                    'color' => 'primary',
                    'size' => 'lg',
                    'class' => 'border',
                ],
            ],
            'default_variants' => [
                'size' => 'lg',
            ],
        ]);

        $this->assertEquals('text-sm px-3 py-2', $cva->base);
        $this->assertCount(2, $cva->variants);
        $this->assertCount(1, $cva->variants['color']->options());
        $this->assertCount(2, $cva->variants['size']->options());
        $this->assertCount(1, $cva->compoundVariants);
        $this->assertCount(1, $cva->defaultVariants);
        $this->assertEquals('lg', $cva->defaultVariants['size']);
    }

    #[Test]
    public function it_works()
    {
        $cva = ClassVariantAuthority::make()
            ->setBase(['text-sm px-3', 'py-2'])
            ->addVariant(
                Variant::make('color')
                    ->addOption(new Option('primary', 'bg-blue-100'))
            )
            ->addVariant(
                Variant::make('size')
                    ->add('sm', 'px-2 py-1')
                    ->add('lg', 'px-4 py-3')
            )
            ->addVariant(
                Variant::make('invalid')
                    ->add(true, 'text-red-500')
            )
            ->addCompoundVariants(
                CompoundVariants::make()
                    ->addCondition('color', 'primary')
                    ->addCondition('size', 'lg')
                    ->setClasses('border')
            )->setDefaultVariants([
                'size' => 'lg',
            ]);

        $this->assertEquals(
            'bg-blue-100 px-4 py-3 text-red-500 border text-lg',
            $cva([
                'color' => 'primary',
                'size' => 'lg',
                'class' => 'text-lg',
                'invalid' => true
            ])
        );
    }
}
