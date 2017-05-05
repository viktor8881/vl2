<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonInvestments for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Investments;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Investments\Form\Factory\InvestmentBuyFormFactory;
use Investments\Form\Factory\InvestmentSellFormFactory;
use Investments\Form\InvestmentBuyForm;
use Investments\Form\InvestmentSellForm;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'investments' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/investments[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z]*',
                        'id' => '[0-9]+'
                    ],
                    'defaults'    => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index'
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

    'form_elements' => [
        'factories' => [
            InvestmentBuyForm::class => InvestmentBuyFormFactory::class,
            InvestmentSellForm::class => InvestmentSellFormFactory::class
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
