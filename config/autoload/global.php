<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;

return [
//    'doctrine' => [
//        'connection' => [
//            'orm_default' => [
//                'driverClass' => PDOMySqlDriver::class,
//                'params'    => [
//                    'host'     => '127.0.0.1',
//                    'user'     => 'root',
//                    'password' => '',
//                    'dbname'   => 'zf3_investment',
//                    'charset'  => 'utf8',
//                ]
//            ],
//        ],
//        'driver' => [
//            __NAMESPACE__ . '_driver' => [
//                'class' => AnnotationDriver::class,
//                'cache' => 'array',
//                'paths' => [
//                    __DIR__ . '/../../model'
//                ]
//            ],
//            'orm_default' => [
//                'drivers' => [
//                    'Model' => __NAMESPACE__ . '_driver'
//                ]
//            ]
//        ]
//    ],

    // Session configuration.
    'session_config' => [
        'cookie_lifetime'     => 60*60*1, // Session cookie will expire in 1 hour.
        'gc_maxlifetime'      => 60*60*24*30, // How long to store session data on server (for 1 month).
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],

];
