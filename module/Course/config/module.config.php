<?php
namespace Course;

use Course\Factory\CacheControllerFactory;
use Course\Factory\IndexControllerFactory;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => IndexControllerFactory::class,
            Controller\CronController::class  => CronControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'course' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/course/:action[/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'currency',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'Course' => __DIR__ . '/../view',
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\CourseManager::class => Service\Factory\CourseManagerFactory::class,
            Service\CacheCourseManager::class => Service\Factory\CacheCourseManagerFactory::class,
            Service\CourseService::class => Service\Factory\CourseServiceFactory::class,
            Service\CacheCourseService::class => InvokableFactory::class,
        ]
    ],

    'view_helpers' => [
        'factories' => [
            View\Helper\HeaderExchange::class => InvokableFactory::class,
        ],
        'aliases' => [
            'headerExchange' => View\Helper\HeaderExchange::class,
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
