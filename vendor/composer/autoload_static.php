<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf33ab5d2b9a67f84a724ee5b7085bb47
{
    public static $prefixLengthsPsr4 = array (
        'l' => 
        array (
            'losnappas\\Ethpress_Token_Roles\\' => 31,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'losnappas\\Ethpress_Token_Roles\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'losnappas\\Ethpress_Token_Roles\\Admin\\Options' => __DIR__ . '/../..' . '/app/Admin/Options.php',
        'losnappas\\Ethpress_Token_Roles\\Etherscan' => __DIR__ . '/../..' . '/app/Etherscan.php',
        'losnappas\\Ethpress_Token_Roles\\Plugin' => __DIR__ . '/../..' . '/app/Plugin.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf33ab5d2b9a67f84a724ee5b7085bb47::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf33ab5d2b9a67f84a724ee5b7085bb47::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf33ab5d2b9a67f84a724ee5b7085bb47::$classMap;

        }, null, ClassLoader::class);
    }
}
