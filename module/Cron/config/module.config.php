<?php

namespace Cron;

use Cron\Factory\AnalysisControllerFactory;
use Cron\Factory\CacheCourseControllerFactory;
use Cron\Factory\CourseControllerFactory;
use Cron\Factory\Exp1ControllerFactory;
use Cron\Factory\IndexControllerFactory;
use Cron\Factory\MessageControllerFactory;
use Cron\Factory\MoexAnalysisControllerFactory;
use Cron\Factory\MoexCacheCourseControllerFactory;
use Cron\Factory\MoexControllerFactory;
use Cron\Factory\MoexMessageControllerFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
            //            'course.tmp' => [
            //                'type' => Literal::class,
            //                'options' => [
            //                    'route'    => '/cron/course/tmp',
            //                    'defaults' => [
            //                        'controller' => Controller\CourseController::class,
            //                        'action'     => 'tmp',
            //                    ],
            //                ],
            //            ],

            //            'cachecourse.firststart' => [
            //                'type' => Literal::class,
            //                'options' => [
            //                    'route'    => '/cron/cache-course/tmp',
            //                    'defaults' => [
            //                        'controller' => Controller\CacheCourseController::class,
            //                        'action'     => 'tmp',
            //                    ],
            //                ],
            //            ],
            //            'cachecourse.filling' => [
            //                'type' => Literal::class,
            //                'options' => [
            //                    'route'    => '/cron/cache-course/tmp1',
            //                    'defaults' => [
            //                        'controller' => Controller\CacheCourseController::class,
            //                        'action'     => 'tmp1',
            //                    ],
            //                ],
            //            ],
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
            //            'analysis.tmp' => [
            //                'type' => Literal::class,
            //                'options' => [
            //                    'route'    => '/cron/analysis/tmp',
            //                    'defaults' => [
            //                        'controller' => Controller\AnalysisController::class,
            //                        'action'     => 'tmp',
            //                    ],
            //                ],
            //            ],

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
            'send-message.moex.index' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/cron/send-message/moex/:exchangeId',
                    'constraints' => [
                        'exchangeId' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\MoexMessageController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            //            'exp1.index' => [
            //                'type' => Literal::class,
            //                'options' => [
            //                    'route'    => '/cron/exp1',
            //                    'defaults' => [
            //                        'controller' => Controller\Exp1Controller::class,
            //                        'action'     => 'index',
            //                    ],
            //                ],
            //            ],

            'cron.moex' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/moex',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'moex',
                    ]
                ]
            ],
//            'cachecourse.moex.firststart' => [
//                'type' => Literal::class,
//                'options' => [
//                    'route'    => '/cron/cache-course/moex/tmp',
//                    'defaults' => [
//                        'controller' => Controller\MoexCacheCourseController::class,
//                        'action'     => 'tmp',
//                    ],
//                ],
//            ],
//            'cachecourse.moex.filling' => [
//                'type' => Literal::class,
//                'options' => [
//                    'route'    => '/cron/cache-course/moex/tmp1',
//                    'defaults' => [
//                        'controller' => Controller\MoexCacheCourseController::class,
//                        'action'     => 'tmp1',
//                    ],
//                ],
//            ]

            'cron.moex.index' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/cron/moex/index/:exchangeId',
                    'constraints' => [
                        'exchangeId' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\MoexController::class,
                        'action'     => 'index',
                    ]
                ]
            ],

            'cachecourse.moex.setcache' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/cron/cache-course/moex/:exchangeId',
                    'constraints' => [
                        'exchangeId' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\MoexCacheCourseController::class,
                        'action'     => 'index'
                    ]
                ]
            ],
            'analysis.moex.index' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/cron/analysis/moex/:exchangeId',
                    'constraints' => [
                        'exchangeId' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\MoexAnalysisController::class,
                        'action'     => 'index',
                    ],
                ],
            ]
        ],
    ],

    'access_filter' => [
        'controllers' => [
            Controller\IndexController::class => [
                ['actions' => ['index', 'moex'], 'allow' => '*'],
            ],
            Controller\CourseController::class => [
                ['actions' => ['receive'], 'allow' => '*'],
            ],
            Controller\CacheCourseController::class => [
                ['actions' => ['fill-cache'], 'allow' => '*'],
            ],
            Controller\AnalysisController::class => [
                ['actions' => ['index'], 'allow' => '*'],
            ],
            Controller\MessageController::class => [
                ['actions' => ['send-message'], 'allow' => '*'],
            ],
            Controller\MoexController::class => [
                ['actions' => '*', 'allow' => '*'],
            ],
            Controller\MoexCacheCourseController::class => [
                ['actions' => ['index'], 'allow' => '*'],
            ],
            Controller\MoexAnalysisController::class => [
                ['actions' => ['index'], 'allow' => '*'],
            ],
            Controller\MoexMessageController::class => [
                ['actions' => ['index'], 'allow' => '*'],
            ]
        ]
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class       => IndexControllerFactory::class,
            Controller\CourseController::class      => CourseControllerFactory::class,
            Controller\CacheCourseController::class => CacheCourseControllerFactory::class,
            Controller\AnalysisController::class    => AnalysisControllerFactory::class,
            Controller\MessageController::class     => MessageControllerFactory::class,
            Controller\Exp1Controller::class        => Exp1ControllerFactory::class,
            Controller\MoexController::class        => MoexControllerFactory::class,
            Controller\MoexCacheCourseController::class => MoexCacheCourseControllerFactory::class,
            Controller\MoexAnalysisController::class => MoexAnalysisControllerFactory::class,
            Controller\MoexMessageController::class  => MoexMessageControllerFactory::class,
        ]
    ],

    'service_manager' => [
        'factories' => [
            Service\ServiceExp1::class    => Service\Factory\ServiceExp1Factory::class,
            Service\MessageService::class => Service\Factory\MessageServiceFactory::class,
            Service\MoexMessageService::class => Service\Factory\MoexMessageServiceFactory::class,
        ]
    ],
];
