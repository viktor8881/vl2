<?php
namespace Investments\Form\Factory;

use Account\Service\AccountManager;
use Course\Service\CourseManager;
use Exchange\Service\ExchangeManager;
use Investments\Form\InvestmentBuyForm;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class InvestmentBuyFormFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

        /** @var AccountManager $accountManager */
        $accountManager = $container->get(AccountManager::class);
        /** @var ExchangeManager $exchangeManager */
        $exchangeManager = $container->get(ExchangeManager::class);
        $courseManager = $container->get(CourseManager::class);
        $form = new InvestmentBuyForm($accountManager->getMainAccount(), $exchangeManager, $courseManager);
        return $form;
    }

}