<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite5156acae18a813873492a74a3f0503e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Pecee\\' => 6,
        ),
        'M' => 
        array (
            'MySuccessStory\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Pecee\\' => 
        array (
            0 => __DIR__ . '/..' . '/pecee/simple-router/src/Pecee',
        ),
        'MySuccessStory\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite5156acae18a813873492a74a3f0503e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite5156acae18a813873492a74a3f0503e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite5156acae18a813873492a74a3f0503e::$classMap;

        }, null, ClassLoader::class);
    }
}
