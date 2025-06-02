# Class Variance Authority for PHP

Class Variance Authority implementation in PHP.

## Installation

```bash
composer require missionx-co/cva-php
```

## Usage

### Basic Example

```php
use MissionX\ClassVariantAuthority\ClassVariantAuthority;
use MissionX\ClassVariantAuthority\Option;

// Create a button component with variants
$button = ClassVariantAuthority::parse([
    'base' => 'text-sm px-3 py-2 rounded-md',
    'variants' => [
        'color' => [
            'primary' => 'bg-blue-500 text-white',
            'secondary' => 'bg-gray-200 text-gray-800',
            'danger' => 'bg-red-500 text-white',
        ],
        'size' => [
            'sm' => 'px-2 py-1 text-xs',
            'md' => 'px-3 py-2 text-sm',
            'lg' => 'px-4 py-3 text-base',
        ],
    ],
    'compound_variants' => [
        [
            'color' => 'primary',
            'size' => 'lg',
            'class' => 'font-bold',
        ],
    ],
    'default_variants' => [
        'size' => 'md',
        'color' => 'primary',
    ],
]);

// Use the component
echo $button(); // "text-sm px-3 py-2 rounded-md bg-blue-500 text-white"
echo $button(['size' => 'lg']); // "text-sm px-4 py-3 rounded-md bg-blue-500 text-white text-base font-bold"
echo $button(['color' => 'secondary']); // "text-sm px-3 py-2 rounded-md bg-gray-200 text-gray-800"
```

### Builder API

You can also use the builder API to create components:

```php
use MissionX\ClassVariantAuthority\ClassVariantAuthority;
use MissionX\ClassVariantAuthority\CompoundVariants;
use MissionX\ClassVariantAuthority\Variant;
use MissionX\ClassVariantAuthority\Option;

$button = ClassVariantAuthority::make()
    ->whereBase('text-sm px-3 py-2 rounded-md')
    ->addVariant(
        Variant::make('color')
            ->add('primary', 'bg-blue-500 text-white')
            ->add('secondary', 'bg-gray-200 text-gray-800')
            ->add('danger', 'bg-red-500 text-white')
    )
    ->addVariant(
        Variant::make('size')
            ->add('sm', 'px-2 py-1 text-xs')
            ->add('md', 'px-3 py-2 text-sm')
            ->add('lg', 'px-4 py-3 text-base')
    )
    ->addCompoundVariants(
        CompoundVariants::make()
            ->addCondition('color', 'primary')
            ->addCondition('size', 'lg')
            ->setClasses('font-bold')
    )
    ->setDefaultVariants([
        'size' => 'md',
        'color' => 'primary',
    ]);

// Use the component
echo $button(['color' => 'danger', 'size' => 'sm']);
```

## Features

-   Type-safe variant definitions
-   Compound variants for complex combinations
-   Default variants
-   Automatic class merging using [TailwindMerge](https://github.com/gehrisandro/tailwind-merge-php)
-   Fluent builder API

## Configuration

You can pass configuration to the underlying TailwindMerge package using the `withConfig` method:

```php
use MissionX\ClassVariantAuthority\Config;

$config = new Config();
$config->tailwindMergeConfig = [
    // TailwindMerge configuration
];

// Add cache for better performance
$config->cache = new YourCacheImplementation();

// Create instance with config
$button = ClassVariantAuthority::withConfig($config)
    ->whereBase('text-base')
    // Add variants...
    ;

// Or add config to existing instance
$button = ClassVariantAuthority::make()
    ->whereBase('text-base')
    ->setConfig($config);
```

## Credits

-   Original JavaScript package: [class-variance-authority](https://www.npmjs.com/package/class-variance-authority)
-   Created by [Mohammed Manssour](https://github.com/mohammedmanssour)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
