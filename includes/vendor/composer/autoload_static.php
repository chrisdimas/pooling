<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5ab0e48d6d019b21187eda23964f4cbe
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
            'Twig\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'P' => 
        array (
            'PhpOption\\' => 10,
            'Pheanstalk\\' => 11,
            'PLGLib\\' => 7,
            'PHPOnCouch\\Exceptions\\' => 22,
            'PHPOnCouch\\Adapter\\' => 19,
            'PHPOnCouch\\' => 11,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/src/Twilio',
        ),
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'PhpOption\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption',
        ),
        'Pheanstalk\\' => 
        array (
            0 => __DIR__ . '/..' . '/pda/pheanstalk/src',
        ),
        'PLGLib\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/includes/pooling',
        ),
        'PHPOnCouch\\Exceptions\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-on-couch/php-on-couch/src/Exceptions',
        ),
        'PHPOnCouch\\Adapter\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-on-couch/php-on-couch/src/Adapter',
        ),
        'PHPOnCouch\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-on-couch/php-on-couch/src',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
    );

    public static $classMap = array (
        'Cache' => __DIR__ . '/..' . '/cosenary/simple-php-cache/cache.class.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5ab0e48d6d019b21187eda23964f4cbe::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5ab0e48d6d019b21187eda23964f4cbe::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5ab0e48d6d019b21187eda23964f4cbe::$classMap;

        }, null, ClassLoader::class);
    }
}