<?php
namespace Cron\Factory;


use Analysis\Service\FigureAnalysisManager;
use Analysis\Service\MovingAverage;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Analysis\Service\TaskPercentAnalysisManager;
use Base\Service\MailService;
use Cron\Controller\MessageController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MessageControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taskOvertimeAnalysisManager = $container->get(TaskOvertimeAnalysisManager::class);
        $taskPercentAnalysisManager = $container->get(TaskPercentAnalysisManager::class);
        $figureAnalysisManager = $container->get(FigureAnalysisManager::class);
        $movingAverage = $container->get(MovingAverage::class);

        $mail = $container->get(MailService::class);

        return new MessageController($taskOvertimeAnalysisManager, $taskPercentAnalysisManager, $figureAnalysisManager, $movingAverage, $mail);
    }

}