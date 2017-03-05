<?php
namespace Base\View\Helper\Factory;

use Base\View\Helper\Menu;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

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
                'id'    => 'users',
                'label' => 'Users',
                'link'  => $url->__invoke('users')
            ],
            [
                'id'       => 'exchange',
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
                'id'       => 'cources',
                'label'    => 'Cources',
                'dropdown' => [[
                                   'id'    => 'cources_Metal',
                                   'label' => 'Metal',
                                   'link'  => $url->__invoke(
                                       'course', ['action' => 'metal']
                                   )
                               ],
                               [
                                   'id'    => 'cources_Currency',
                                   'label' => 'Currency',
                                   'link'  => $url->__invoke(
                                       'course', ['action' => 'currency']
                                   )
                               ]],

            ],
            [
                'id'    => 'investments',
                'label' => 'Investments',
                'link'  => $url->__invoke('investments')
            ],
            [
                'id'    => 'tasks',
                'label' => 'Tasks',
                'link'  => $url->__invoke('tasks')
            ],
        ];
        $helper = new Menu($items);
        return $helper;
    }

}
