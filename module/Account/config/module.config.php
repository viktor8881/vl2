<?php
namespace Account;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
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
            'account' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/account[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z]*'
                    ],
                    'defaults'    => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index'
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\AccountManager::class => Service\Factory\AccountManagerFactory::class,
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            'Account' => __DIR__ . '/../view',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\LinkAdd::class => InvokableFactory::class,
            View\Helper\LinkSub::class => InvokableFactory::class,
        ],
        'aliases' => [
            'linkAdd' => View\Helper\LinkAdd::class,
            'linkSub' => View\Helper\LinkSub::class,
        ]
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
