<?php
namespace Application\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\View\Helper\Menu;

class ViewHelperMenuFactory implements FactoryInterface
{


    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var \Zend\View\HelperPluginManager $helperManager */
        $helperManager = $container->get('ViewHelperManager');
        $url = $helperManager->get('url');

        $items = [
            [
                'id' => 'home',
                'label' => 'Home',
                'link' => $url->__invoke('home')
            ],
            [
                'id' => 'about',
                'label' => 'About',
                'link' => $url->__invoke('about')
            ],
        ];
        $helper = new Menu($items);
        return $helper;
    }

}
