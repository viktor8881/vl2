<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonAnalysis for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Analysis;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'service_manager' => [
        'factories' => [
            Service\TechnicalAnalysis::class           => InvokableFactory::class,
            Service\AnalysisService::class             => Service\Factory\AnalysisServiceFactory::class,
            Service\FigureAnalysisManager::class       => Service\Factory\FigureAnalysisManagerFactory::class,
            Service\TaskOvertimeAnalysisManager::class => Service\Factory\TaskOvertimeAnalysisManagerFactory::class,
            Service\TaskPercentAnalysisManager::class  => Service\Factory\TaskPercentAnalysisManagerFactory::class,
            Service\MovingAverage::class               => Service\Factory\MovingAverageFactory::class,
            Service\MoexAnalysisService::class         => Service\Factory\MoexAnalysisServiceFactory::class,
            Service\MoexFigureAnalysisManager::class   => Service\Factory\MoexFigureAnalysisManagerFactory::class,
            Service\MoexOvertimeAnalysisManager::class => Service\Factory\MoexOvertimeAnalysisManagerFactory::class,
            Service\MoexPercentAnalysisManager::class  => Service\Factory\MoexPercentAnalysisManagerFactory::class,
        ]
    ],

    'view_helpers' => [
        'factories' => [
            View\Helper\FigureName::class   => InvokableFactory::class,
            View\Helper\FigurePeriod::class => InvokableFactory::class,
        ],
        'aliases'   => [
            'figureName'    => View\Helper\FigureName::class,
            'figurePeriod'  => View\Helper\FigurePeriod::class,
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
