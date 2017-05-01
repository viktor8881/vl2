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

        $items = [];

        $identity = $helperManager->get('identity');
        if ($identity->__invoke()) {
            $items = [
                [
                    'id'    => 'about',
                    'label' => 'О проекте',
                    'link'  => $url->__invoke('about')
                ],
                [
                    'id'    => 'users',
                    'label' => 'Пользователи',
                    'link'  => $url->__invoke('users')
                ],
                [
                    'id'       => 'exchange',
                    'label'    => 'Exchange',
                    'dropdown' => [[
                                       'id'    => 'List_Metal',
                                       'label' => 'Металы',
                                       'link'  => $url->__invoke('metal.list')
                                   ],
                                   [
                                       'id'    => 'List_Currency',
                                       'label' => 'Валюты',
                                       'link'  => $url->__invoke('currency.list')
                                   ]]
                ],
                [
                    'id'       => 'cources',
                    'label'    => 'Курсы',
                    'dropdown' => [[
                                       'id'    => 'cources_Metal',
                                       'label' => 'Металы',
                                       'link'  => $url->__invoke(
                                           'course', ['action' => 'metal']
                                       )
                                   ],
                                   [
                                       'id'    => 'cources_Currency',
                                       'label' => 'Валюты',
                                       'link'  => $url->__invoke(
                                           'course', ['action' => 'currency']
                                       )
                                   ]],

                ],
                [
                    'id'    => 'investments',
                    'label' => 'Инвестиции',
                    'link'  => $url->__invoke('investments')
                ],
                [
                    'id'    => 'tasks',
                    'label' => 'Задачи',
                    'link'  => $url->__invoke('tasks')
                ],
            ];
        }

        $helper = new Menu($items);
        return $helper;
    }

}
