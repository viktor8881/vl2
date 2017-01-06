<?php
namespace Application\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\View\Helper\Menu;

class ViewHelperMenuFactory implements FactoryInterface
{


    public function __invoke(ContainerInterface $container, $requestedName,
        array $options = null
    ) {
        /** @var \Zend\View\HelperPluginManager $helperManager */
        $helperManager = $container->get('ViewHelperManager');
        $url = $helperManager->get('url');

        $items = [
            [
                'id'    => 'home',
                'label' => 'Home',
                'link'  => $url->__invoke('home')
            ],
            [
                'id'    => 'about',
                'label' => 'About',
                'link'  => $url->__invoke('about')
            ],
            [
                'id'    => 'Users',
                'label' => 'Users',
                'link'  => $url->__invoke('users')
            ],
            [
                'id'       => 'Exchange',
                'label'    => 'Exchange',
                'dropdown' => [[
                                   'id'    => 'List_Metal',
                                   'label' => 'Metal',
                                   'link'  => $url->__invoke('metal.list')
                               ],
                               [
                                   'id'    => 'List_Currency',
                                   'label' => 'Currency',
                                   'link'  => $url->__invoke('currency.list')
                               ]]
            ],
            [
                'id'       => 'Cources',
                'label'    => 'Cources',
                'dropdown' => [[
                                   'id'    => 'Cources_Metal',
                                   'label' => 'Metal',
                                   'link'  => $url->__invoke(
                                       'courses', ['action' => 'metal']
                                   )
                               ],
                               [
                                   'id'    => 'Cources_Currency',
                                   'label' => 'Currency',
                                   'link'  => $url->__invoke(
                                       'courses', ['action' => 'currency']
                                   )
                               ]]
            ],
        ];
        $helper = new Menu($items);
        return $helper;
    }

}
