<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonBase for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Base;

use Base\View\Helper\Factory\ViewHelperMenuFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class               => ViewHelperMenuFactory::class,
            View\Helper\Breadcrumbs::class        => InvokableFactory::class,
            View\Helper\PageHeader::class         => InvokableFactory::class,
            View\Helper\IconAdd::class            => InvokableFactory::class,
            View\Helper\IconDelete::class         => InvokableFactory::class,
            View\Helper\IconDown::class           => InvokableFactory::class,
            View\Helper\IconEdit::class           => InvokableFactory::class,
            View\Helper\IconSub::class            => InvokableFactory::class,
            View\Helper\IconUp::class             => InvokableFactory::class,
            View\Helper\FormatInt::class          => InvokableFactory::class,
            View\Helper\FormatMetal::class        => InvokableFactory::class,
            View\Helper\FormatMoney::class        => InvokableFactory::class,
            View\Helper\FormatNumber::class       => InvokableFactory::class,
            View\Helper\FormatPercent::class      => InvokableFactory::class,
            View\Helper\Plural::class             => InvokableFactory::class,
            View\Helper\PluralDays::class         => InvokableFactory::class,
            View\Helper\PluralDaysGenitive::class => InvokableFactory::class,
            View\Helper\FormHelper::class         => InvokableFactory::class,
            View\Helper\PageMessage::class        => InvokableFactory::class,
        ],
        'aliases'   => [
            'mainMenu'           => View\Helper\Menu::class,
            'pageBreadcrumbs'    => View\Helper\Breadcrumbs::class,
            'pageHeader'         => View\Helper\PageHeader::class,
            'iconAdd'            => View\Helper\IconAdd::class,
            'iconDelete'         => View\Helper\IconDelete::class,
            'iconDown'           => View\Helper\IconDown::class,
            'iconEdit'           => View\Helper\IconEdit::class,
            'iconSub'            => View\Helper\IconSub::class,
            'iconUp'             => View\Helper\IconUp::class,
            'formatInt'          => View\Helper\FormatInt::class,
            'formatMetal'        => View\Helper\FormatMetal::class,
            'formatMoney'        => View\Helper\FormatMoney::class,
            'formatNumber'       => View\Helper\FormatNumber::class,
            'formatPercent'      => View\Helper\FormatPercent::class,
            'plural'             => View\Helper\Plural::class,
            'pluralDays'         => View\Helper\PluralDays::class,
            'pluralDaysGenitive' => View\Helper\PluralDaysGenitive::class,
            'formHelper'         => View\Helper\FormHelper::class,
            'pageMessage'         => View\Helper\PageMessage::class,
        ]
    ],

    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\AccessPlugin::class => InvokableFactory::class,
        ],
        'aliases'   => [
            'access' => Controller\Plugin\AccessPlugin::class,
        ]
    ],


];
