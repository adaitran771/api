<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb3e68c06bc3612e7e3ea0268aeb5503e
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb3e68c06bc3612e7e3ea0268aeb5503e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb3e68c06bc3612e7e3ea0268aeb5503e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb3e68c06bc3612e7e3ea0268aeb5503e::$classMap;

        }, null, ClassLoader::class);
    }
}
