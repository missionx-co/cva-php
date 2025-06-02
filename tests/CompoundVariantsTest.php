<?php

namespace MissionX\ClassVariantAuthority\Tests;

use MissionX\ClassVariantAuthority\CompoundVariants;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CompoundVariantsTest extends TestCase
{
    public CompoundVariants $compoundVariants;

    protected function setUp(): void
    {
        parent::setUp();
        $this->compoundVariants = new CompoundVariants;
    }

    #[Test]
    public function it_matches_conditions()
    {
        $this->compoundVariants->addCondition('color', 'primary')
            ->addCondition('size', 'lg');

        $this->assertTrue(
            $this->compoundVariants->match([
                'color' => 'primary',
                'size' => 'lg',
                'font' => 'inter',
            ])
        );
    }

    #[Test]
    public function it_does_not_match_conditions()
    {
        $this->compoundVariants->addCondition('color', 'primary')
            ->addCondition('size', 'lg');

        $this->assertFalse(
            $this->compoundVariants->match([
                'color' => 'succss',
                'size' => 'lg',
                'font' => 'inter',
            ])
        );

        $this->assertFalse(
            $this->compoundVariants->match([
                'color' => 'succss',
            ])
        );

        $this->assertFalse(
            $this->compoundVariants->match([])
        );
    }

    #[Test]
    public function it_generates_hash()
    {
        $this->compoundVariants->addCondition('color', 'primary')
            ->addCondition('size', 'lg');

        $other = new CompoundVariants;
        $other->addCondition('size', 'lg')->addCondition('color', 'primary');

        $this->assertEquals($this->compoundVariants->hash(), $other->hash());
    }

    #[Test]
    public function it_pases_compound_variants()
    {
        $compoundVariants = CompoundVariants::parse([
            'color' => 'primary',
            'size' => 'lg',
            'class' => 'bg-blue-100',
        ]);

        $this->assertEquals('primary', $compoundVariants->conditions()['color']);
        $this->assertEquals('lg', $compoundVariants->conditions()['size']);
        $this->assertArrayNotHasKey('class', $compoundVariants->conditions());

        $this->assertEquals('bg-blue-100', $compoundVariants->classes());
    }
}
