<?php
namespace Course;

use Course\Factory\IndexControllerFactory;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'course/currency' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/course/:action/:id',
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
    'service_manager' => [
        'factories' => [
            Service\CourseManager::class => Service\Factory\CourseManagerFactory::class,
            Service\CacheCourseManager::class => Service\Factory\CacheCourseManagerFactory::class,
        ]
    ],

    'view_manager' => [
        'template_path_stack' => [
            'Course' => __DIR__ . '/../view',
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
