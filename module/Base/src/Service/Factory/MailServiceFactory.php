<?php
namespace Base\Service\Factory;

use Base\Service\MailService;
use Interop\Container\ContainerInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\Renderer\PhpRenderer;


class MailServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mailOptions = $container->get('config')['mail'];
        $transport = new SmtpTransport();
        $options = new SmtpOptions($mailOptions['smtpOptions']);
        $transport->setOptions($options);

        $message = new Message();
        $message->addTo($mailOptions['addresses']['adminEmail']);
        $message->addFrom($mailOptions['addresses']['siteEmail']);

        $render = $container->get(PhpRenderer::class);

        return new MailService($message, $transport, $render);
    }

}