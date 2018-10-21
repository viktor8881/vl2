<?php
namespace Exchange;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;


return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'metal.list' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/metal/list',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'metal',
                    ],
                ]
            ],
            'currency.list' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/currency/list',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'currency',
                    ],
                ]
            ],
            'stock.list' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/stock/list',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'stock',
                    ],
                ]
            ]
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\ExchangeManager::class => Service\Factory\ExchangeManagerFactory::class,
        ]
    ],

    'view_manager' => [
        'template_path_stack' => [
            'Exchange' => __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
