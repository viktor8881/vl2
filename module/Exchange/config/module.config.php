<?php
namespace Exchange;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'metal/list' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/metal/list',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'metal',
                    ],
                ]
            ],
            'currency/list' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/currency/list',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'currency',
                    ],
                ]
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'Exchange' => __DIR__ . '/../view',
        ],
    ],
];
