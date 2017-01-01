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
    'service_manager'=>[
        'factories' => [
            Model\Exchange\Manager::class => Model\Exchange\FactoryManager::class,
        ],
        'aliases' => [
            'ManagerExchange' => Model\Exchange\Manager::class
        ],
    ],
];
