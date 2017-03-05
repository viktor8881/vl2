<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonInvestments for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Investments;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'investments' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/investments',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\IndexControllerFactory::class
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'Investments' => __DIR__ . '/../view',
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\InvestmentsManager::class => Service\Factory\InvestmentsManagerFactory::class,
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
