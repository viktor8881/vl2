<?php

return [
    'navigation' => [
        'default' => [
            [
                'label' => 'Home',
                'route' => 'home',
                'pages' => [
                    [
                        'label' => 'О проекте',
                        'route' => 'about',
                    ],
                    [
                        'label' => 'Счет',
                        'route'     => 'account',
                        'pages' => [
                            [
                                'label' => 'Пополнить счет',
                                'route'     => 'account',
                                'action'     => 'add',
                            ],
                            [
                                'label' => 'Списать счет',
                                'route'     => 'account',
                                'action'     => 'sub',
                            ],
                        ],
                    ],
                    [
                        'label'     => 'Курс валюты',
                        'route'     => 'course',
                        'action'    => 'currency',
                    ],
                    [
                        'label'     => 'Курс метала',
                        'route'     => 'course',
                        'action'    => 'metal',
                    ],
                    [
                        'label' => 'Металы',
                        'route' => 'metal.list',
                    ],
                    [
                        'label' => 'Валюты',
                        'route' => 'currency.list',
                    ],
                    [
                        'label' => 'Инвестиции',
                        'route' => 'investments',
                        'pages' => [
                            [
                                'label' => 'Купить инвестиции',
                                'route' => 'investments',
                                'action'    => 'buy',
                            ],
                            [
                                'label' => 'Продать инвестиции',
                                'route' => 'investments',
                                'action'    => 'sell',
                            ],
                            [
                                'label' => 'Удалить инвестиции',
                                'route' => 'investments',
                                'action'    => 'delete',
                            ],
                        ]
                    ],
                    [
                        'label' => 'Задания',
                        'route' => 'tasks',
                        'pages' => [
                            [
                                'label'     => 'Добавить задание "процент за период"',
                                'route'     => 'task.percent',
                                'action'    => 'add',
                            ],
                            [
                                'label'     => 'Изменить задание "процент за период"',
                                'route'     => 'task.percent',
                                'action'    => 'edit',
                            ],
                            [
                                'label'     => 'Удалить задание "процент за период"',
                                'route'     => 'task.percent',
                                'action'    => 'delete',
                            ],
                            [
                                'label'     => 'Добавить задание "последовательный рост/падение"',
                                'route'     => 'task.overtime',
                                'action'    => 'add',
                            ],
                            [
                                'label'     => 'Изменить задание "последовательный рост/падение"',
                                'route'     => 'task.overtime',
                                'action'    => 'edit',
                            ],
                            [
                                'label'     => 'Удалить задание "последовательный рост/падение"',
                                'route'     => 'task.overtime',
                                'action'    => 'delete',
                            ],
                        ]
                    ],
                    [
                        'label' => 'Пользователи',
                        'route' => 'users',
                        'pages' => [
                            [
                                'label'     => 'Параметры пользователя',
                                'route'     => 'users',
                                'action'    => 'view',
                            ],
                            [
                                'label'     => 'Добавить пользователя',
                                'route'     => 'users',
                                'action'    => 'add',
                            ],
                            [
                                'label'     => 'Редактировать пользователя',
                                'route'     => 'users',
                                'action'    => 'edit',
                            ],
                            [
                                'label'     => 'Изменить пароль пользователя',
                                'route'     => 'users',
                                'action'    => 'change-password',
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'navigation' => Zend\Navigation\Service\DefaultNavigationFactory::class,
        ],
    ],
];
