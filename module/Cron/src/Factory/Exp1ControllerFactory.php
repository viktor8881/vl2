<?php
namespace Cron\Factory;


use Account\Service\AccountManager;
use Course\Service\CacheCourseManager;
use Course\Service\CourseManager;
use Cron\Controller\Exp1Controller;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Investments\Service\InvestmentsManager;
use Zend\ServiceManager\Factory\FactoryInterface;

class Exp1ControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $tmpDir = $container->get('Config')['tmp_dir'];
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir);
        }

        $exchangeManager = $container->get(ExchangeManager::class);
        $courseManager = $container->get(CourseManager::class);
        $cacheCourseManager = $container->get(CacheCourseManager::class);
        $investManager = $container->get(InvestmentsManager::class);
        $accountManager = $container->get(AccountManager::class);
        $controller = new Exp1Controller($exchangeManager, $courseManager, $cacheCourseManager, $investManager, $accountManager, $tmpDir);
        return $controller;
    }

}