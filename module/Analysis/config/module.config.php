<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonAnalysis for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Analysis;

use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'service_manager' => [
        'factories' => [
            Service\TechnicalAnalysis::class => InvokableFactory::class,
        ]
    ],
];
