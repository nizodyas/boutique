<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit40368be59d18921d94c95cf73b639ffc
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit40368be59d18921d94c95cf73b639ffc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit40368be59d18921d94c95cf73b639ffc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
