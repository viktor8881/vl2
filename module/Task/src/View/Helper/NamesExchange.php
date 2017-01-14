<?php
namespace Task\View\Helper;

use Exchange\Entity\Exchange;
use Task\Entity\Task;
use Zend\View\Helper\AbstractHelper;

class NamesExchange extends AbstractHelper
{

    /**
     * @param Exchange[] $exchanges
     * @param string $separate
     *
     * @return string
     */
    public function __invoke(array $exchanges, $separate = '<br /> ')
    {
        $result = [];
        /**
         * @var $exchange Exchange
         */
        foreach($exchanges as $exchange) {
            $result[] = $this->view->escapeHtml($exchange->getName());
        }
        return implode($separate, $result);
    }
        
}
