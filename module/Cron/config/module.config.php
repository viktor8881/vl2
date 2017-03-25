<?php

namespace Cron;

use Cron\Factory\AnalysisControllerFactory;
use Cron\Factory\CacheCourseControllerFactory;
use Cron\Factory\CourseControllerFactory;
use Cron\Factory\IndexControllerFactory;
use Cron\Factory\MessageControllerFactory;
use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'cron' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'cron.analysis' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/analysis',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'analysis',
                    ],
                ],
            ],

            'course.receive' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/course/receive',
                    'defaults' => [
                        'controller' => Controller\CourseController::class,
                        'action'     => 'receive',
                    ],
                ],
            ],
            'course.tmp' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/course/tmp',
                    'defaults' => [
                        'controller' => Controller\CourseController::class,
                        'action'     => 'tmp',
                    ],
                ],
            ],

            'cachecourse.firststart' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/cache-course/tmp',
                    'defaults' => [
                        'controller' => Controller\CacheCourseController::class,
                        'action'     => 'tmp',
                    ],
                ],
            ],
            'cachecourse.filling' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/cache-course/tmp1',
                    'defaults' => [
                        'controller' => Controller\CacheCourseController::class,
                        'action'     => 'tmp1',
                    ],
                ],
            ],
            'cachecourse.setcache' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/cache-course',
                    'defaults' => [
                        'controller' => Controller\CacheCourseController::class,
                        'action'     => 'fill-cache',
                    ],
                ],
            ],

            'analysis.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/analysis',
                    'defaults' => [
                        'controller' => Controller\AnalysisController::class,
                        'action'     => 'index',
                    ],
                ],
            ],


            'analysis.tmp' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/analysis/tmp',
                    'defaults' => [
                        'controller' => Controller\AnalysisController::class,
                        'action'     => 'tmp',
                    ],
                ],
            ],

            'send-message.tmp' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/send-message/tmp',
                    'defaults' => [
                        'controller' => Controller\MessageController::class,
                        'action'     => 'tmp',
                    ],
                ],
            ],

            'send-message.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/send-message',
                    'defaults' => [
                        'controller' => Controller\MessageController::class,
                        'action'     => 'send-message',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class       => IndexControllerFactory::class,
            Controller\CourseController::class      => CourseControllerFactory::class,
            Controller\CacheCourseController::class => CacheCourseControllerFactory::class,
            Controller\AnalysisController::class    => AnalysisControllerFactory::class,
            Controller\MessageController::class     => MessageControllerFactory::class
        ],
    ],
];
