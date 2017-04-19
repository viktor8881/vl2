<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;

class Module
{
    const VERSION = '3.0.2dev';

    public function init(ModuleManager $manager)
    {
        // Получаем менеджер событий.
        $eventManager = $manager->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Регистрируем метод-обработчик.
//        $sharedEventManager->attach(__NAMESPACE__, 'dispatch', [$this, 'onDispatch'], 100);
//        pr('Module::init');
    }

    // Обработчик события.
    public function onDispatch(MvcEvent $event)
    {

        // Получаем контроллер, к которому был отправлен HTTP-запрос.
        $controller = $event->getTarget();
        // Получаем полностью определенное имя класса контроллера.
        $controllerClass = get_class($controller);
        // Получаем имя модуля контроллера.
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        // Переключаем лэйаут только для контроллеров, принадлежащих нашему модулю.
        if ($moduleNamespace == __NAMESPACE__) {
            $viewModel = $event->getViewModel();
            $viewModel->setTemplate('layout/alt-layout');
        }
    }

    public function onBootstrap(MvcEvent $event)
    {
//        $config = $event->getApplication()->getServiceManager()->get('Config');
//        print_r($config['router']);
//        die('ads');

        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        // Следующая строка инстанцирует SessionManager и автоматически
        // делает его выбираемым 'по умолчанию'.
        $sessionManager = $serviceManager->get(SessionManager::class);
    }

    public function getConfig()
    {
//        pr('Module::getConfig');
        return include __DIR__ . '/../config/module.config.php';
    }
}
