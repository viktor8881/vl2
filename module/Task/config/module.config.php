<?php
namespace Task;

use Task\Factory\IndexControllerFactory;
use Task\Factory\PercentControllerFactory;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => IndexControllerFactory::class,
            Controller\PercentController::class => PercentControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'tasks' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/tasks',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'task-percent' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/task/percent/:action[/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\PercentController::class,
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\TaskManager::class => Service\Factory\TaskManagerFactory::class,
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            'Task' => __DIR__ . '/../view',
        ],
    ],

    'view_helpers' => [
        'factories' => [
            View\Helper\Name::class => InvokableFactory::class,
            View\Helper\LinkEdit::class => InvokableFactory::class,
            View\Helper\LinkDelete::class => InvokableFactory::class,
            View\Helper\NamesExchange::class => InvokableFactory::class,
        ],
        'aliases' => [
            'taskName' => View\Helper\Name::class,
            'linkEdit' => View\Helper\LinkEdit::class,
            'linkDelete' => View\Helper\LinkDelete::class,
            'namesExchange' => View\Helper\NamesExchange::class,
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