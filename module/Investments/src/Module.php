<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonInvestments for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Investments;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
