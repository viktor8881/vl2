<?php
namespace Course;

use Course\Factory\IndexControllerFactory;
use Course\Factory\MoexControllerFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => IndexControllerFactory::class,
            Controller\MoexController::class => MoexControllerFactory::class,
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
            'course-moex' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/course/moex-data/:id',
                    'constraints' => [
                        'id' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\MoexController::class,
                        'action' => 'index',
                    ],
                ],
            ]
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'Course' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\CourseManager::class            => Service\Factory\CourseManagerFactory::class,
            Service\CourseService::class            => Service\Factory\CourseServiceFactory::class,
            Service\CacheCourseManager::class       => Service\Factory\CacheCourseManagerFactory::class,
            Service\CacheCourseService::class       => Service\Factory\CacheCourseServiceFactory::class,
            Service\MoexService::class              => Service\Factory\MoexServiceFactory::class,
            Service\MoexManager::class              => Service\Factory\MoexManagerFactory::class,
            Service\MoexCacheCourseManager::class   => Service\Factory\MoexCacheCourseManagerFactory::class,
            Service\MoexCacheCourseService::class   => Service\Factory\MoexCacheCourseServiceFactory::class
        ]
    ],

    'view_helpers' => [
        'factories' => [
            View\Helper\HeaderExchange::class       => InvokableFactory::class,
            View\Helper\BlockAction::class          => InvokableFactory::class,
            View\Helper\BlockAnalysis::class        => InvokableFactory::class,
            View\Helper\OvertimeAnalysis::class     => InvokableFactory::class,
            View\Helper\PercentAnalysis::class      => InvokableFactory::class,
            View\Helper\FigureAnalysis::class       => InvokableFactory::class,
            View\Helper\TableStock::class           => InvokableFactory::class,
            View\Helper\MaxOneDayTableStock::class  => InvokableFactory::class,
        ],
        'aliases' => [
            'headerExchange'        => View\Helper\HeaderExchange::class,
            'blockAnalysis'         => View\Helper\BlockAnalysis::class,
            'blockAction'           => View\Helper\BlockAction::class,
            'overtimeAnalysis'      => View\Helper\OvertimeAnalysis::class,
            'percentAnalysis'       => View\Helper\PercentAnalysis::class,
            'figureAnalysis'        => View\Helper\FigureAnalysis::class,
            'tableStock'            => View\Helper\TableStock::class,
            'maxOneDayTableStock'   => View\Helper\MaxOneDayTableStock::class,
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
