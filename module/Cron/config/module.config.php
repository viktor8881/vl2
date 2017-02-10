<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonCron for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cron;

use Cron\Factory\CourseControllerFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Mvc\Controller\LazyControllerAbstractFactory;

return [
    'router' => [
        'routes' => [
            'course.receive' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/course-receive',
                    'defaults' => [
                        'controller' => Controller\CourseController::class,
                        'action'     => 'receive',
                    ],
                ],
            ],
            'course.fillcache' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/cron/course-fill-cache',
                    'defaults' => [
                        'controller' => Controller\CourseController::class,
                        'action'     => 'fill-cache',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\CourseController::class => CourseControllerFactory::class
        ],
    ],
];
