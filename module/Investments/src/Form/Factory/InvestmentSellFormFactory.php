<?php
namespace Investments\Form\Factory;

use Account\Service\AccountManager;
use Course\Service\CourseManager;
use Exchange\Service\ExchangeManager;
use Investments\Form\InvestmentBuyForm;
use Investments\Form\InvestmentSellForm;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class InvestmentSellFormFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

        /** @var AccountManager $accountManager */
        $accountManager = $container->get(AccountManager::class);
        /** @var ExchangeManager $exchangeManager */
        $exchangeManager = $container->get(ExchangeManager::class);
        $courseManager = $container->get(CourseManager::class);
        $form = new InvestmentSellForm($accountManager, $exchangeManager, $courseManager);
        return $form;
    }

}