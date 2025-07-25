<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit652899b34da171aac8f751a6c639281f
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SCF\\Meta\\' => 9,
            'SCF\\Forms\\' => 10,
        ),
        'A' => 
        array (
            'ACF\\Blocks\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SCF\\Meta\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/Meta',
        ),
        'SCF\\Forms\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/forms',
        ),
        'ACF\\Blocks\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/Blocks',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit652899b34da171aac8f751a6c639281f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit652899b34da171aac8f751a6c639281f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit652899b34da171aac8f751a6c639281f::$classMap;

        }, null, ClassLoader::class);
    }
}
