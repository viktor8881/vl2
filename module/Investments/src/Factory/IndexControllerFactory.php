<?php
namespace Investments\Factory;


use Account\Service\AccountManager;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Investments\Controller\IndexController;
use Investments\Form\InvestmentBuyForm;
use Investments\Form\InvestmentSellForm;
use Investments\Service\InvestmentsManager;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var InvestmentsManager $investmentsManager */
        $investmentsManager = $container->get(InvestmentsManager::class);

        /** @var FormElementManager $elementManager */
        $elementManager = $container->get(FormElementManager::class);
        $formBuy = $elementManager->get(InvestmentBuyForm::class);
        $formSell = $elementManager->get(InvestmentSellForm::class);

        /** @var AccountManager $accountManager */
        $accountManager = $container->get(AccountManager::class);

        /** @var ExchangeManager $exchangeManager */
        $exchangeManager = $container->get(ExchangeManager::class);
        return new IndexController($investmentsManager, $formBuy, $formSell, $accountManager, $exchangeManager);
    }

}
