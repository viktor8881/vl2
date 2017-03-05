<?php
namespace Task;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Task\Factory\IndexControllerFactory;
use Task\Factory\OvertimeControllerFactory;
use Task\Factory\PercentControllerFactory;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers'     => [
        'factories' => [
            Controller\IndexController::class    => IndexControllerFactory::class,
            Controller\PercentController::class  => PercentControllerFactory::class,
            Controller\OvertimeController::class => OvertimeControllerFactory::class,
        ],
    ],
    'router'          => [
        'routes' => [
            'tasks'         => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/tasks',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'task.percent'  => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/task/percent/:action[/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z]*',
                        'id'     => '[0-9]*',
                    ],
                    'defaults'    => [
                        'controller' => Controller\PercentController::class,
                    ],
                ],
            ],
            'task.overtime' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/task/overtime/:action[/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z]*',
                        'id'     => '[0-9]*',
                    ],
                    'defaults'    => [
                        'controller' => Controller\OvertimeController::class,
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\TaskPercentManager::class => Service\Factory\TaskPercentManagerFactory::class,
            Service\TaskOvertimeManager::class => Service\Factory\TaskOvertimeManagerFactory::class,
        ]
    ],
    'view_manager'    => [
        'template_path_stack' => [
            'Task' => __DIR__ . '/../view',
        ],
    ],

    'view_helpers' => [
        'factories' => [
            View\Helper\Name::class          => InvokableFactory::class,
            View\Helper\LinkEdit::class      => InvokableFactory::class,
            View\Helper\LinkDelete::class    => InvokableFactory::class,
            View\Helper\NamesExchange::class => InvokableFactory::class,
        ],
        'aliases'   => [
            'taskName'      => View\Helper\Name::class,
            'linkEdit'      => View\Helper\LinkEdit::class,
            'linkDelete'    => View\Helper\LinkDelete::class,
            'namesExchange' => View\Helper\NamesExchange::class,
        ]
    ],
    'doctrine'     => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default'             => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
