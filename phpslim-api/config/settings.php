<?php

// Settings
$settings = [
    'environment' => 'development' // (development|production)
];

// Should be set to 0 (E_NONE) in production
error_reporting($settings['environment'] == 'development' ? E_ALL : 0);

// Should be set to '0' in production
ini_set('display_errors', $settings['environment'] == 'development' ? '1' : '0');

// Timezone
date_default_timezone_set('Europe/Madrid');

// Path settings
$settings['paths']['root'] = dirname(__DIR__);
$settings['paths']['vendor'] = $settings['paths']['root'] . DIRECTORY_SEPARATOR . 'vendor';
$settings['paths']['database'] = $settings['paths']['root'] . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ReForms2.sqlite3';
$settings['paths']['templates'] = $settings['paths']['root'] . DIRECTORY_SEPARATOR . 'templates';
$settings['paths']['logs'] = $settings['paths']['root'] . DIRECTORY_SEPARATOR . 'logs';

// Error Handling Middleware settings
$settings['error'] = [

    // Should be set to false in production
    'display_error_details' => $settings['environment'] == 'development',

    // Parameter is passed to the default ErrorHandler
    // View in rendered output by enabling the 'displayErrorDetails' setting.
    // For the console and unit tests we also disable it
    'log_errors' => true,

    // Display error details in error log
    'log_error_details' => true,
];

$settings['logger'] = [
    'defaultLevel' => $settings['environment'] == 'development' ? \Monolog\Level::Debug : \Monolog\Level::Critical,
    'channels' => [
        'default'  => [
            'path' => isset($_ENV['docker']) ? 'php://stdout' : $settings['paths']['logs'] . DIRECTORY_SEPARATOR . 'default.log',
            'name' => 'ReForms::Default'
        ],
        'installer' => [
            'path' => isset($_ENV['docker']) ? 'php://stdout' : $settings['paths']['logs'] . DIRECTORY_SEPARATOR . 'installer.log',
            'name' => 'ReForms::Installer'
        ],
        'database' => [
            'path' => isset($_ENV['docker']) ? 'php://stdout' : $settings['paths']['logs'] . DIRECTORY_SEPARATOR . 'database.log',
            'name' => 'ReForms::Database'
        ]
    ]
];

// Database settings
$settings['db'] = [
    'driver' => 'sqlite',
    'host' => '',
    'username' => '',
    'database' => $settings['paths']['database'],
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'flags' => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Set character set
        //PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci' // BUG: https://bugs.php.net/bug.php?id=81576
    ],
    'upgradeSchemaPath' => $settings['paths']['root'] . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db-schema.php'
];

$settings['twig'] = [
    'path' =>  $settings['paths']['templates'],
    'options' =>  ['auto_reload' => true, 'cache' => $settings['environment'] == 'development' ? false : dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'twig_cache']
];

$settings['common'] = [
    'defaultResultsPage' => 64,
    'allowSignUp' => true,
    'locale' => 'en' // (en | es | gl)
];

// TODO: similar artists will be matched with this algorithm relevance order (based on cached data)
//$settings['similarArtistsPreferredAlgorithmRelevance'] = ["lastFMSimilar", "lastFMTag", "musicbrainzGenre", "fileID3TagGenre"];

$settings['jwt'] = [
    'passphrase' => 'Fuw{{-E{E-,5^0]~*TTuG=pF0\u%e;F2qxjv9IVFn7PYRgfM>4ML/Mey>a|e$;>u' // WARNING: for security reasons, generate a random string for using as your OWN (not default) passphrase
];

$settings['phpRequiredExtensions'] = array('pdo_sqlite', 'mbstring');


return $settings;
