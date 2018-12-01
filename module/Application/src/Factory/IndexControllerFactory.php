<?php
namespace Application\Factory;


use Analysis\Service\MoexAnalysisService;
use Analysis\Service\MovingAverage;
use Application\Controller\IndexController;
use Course\Service\CourseManager;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ExchangeManager $exchangeManager */
        $exchangeManager = $container->get(ExchangeManager::class);
        /** @var CourseManager $courseManager */
        $courseManager = $container->get(CourseManager::class);
        /** @var MovingAverage $movingAverage */
        $movingAverage = $container->get(MovingAverage::class);
        /** @var MoexAnalysisService $analysisService */
        $analysisService = $container->get(MoexAnalysisService::class);

        return new IndexController($exchangeManager, $courseManager, $movingAverage, $analysisService);
    }

}
