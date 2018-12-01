<?php
namespace Course\View\Helper;


use Analysis\Entity\MoexFigureAnalysis;
use Exchange\Entity\Exchange;
use Zend\View\Helper\AbstractHelper;

class BlockAction extends AbstractHelper
{


    public function __invoke(Exchange $exchange)
    {
        $html = [];
        if ($exchange->getFavorite()) {
            $html[] = '<a href="#" onclick="unFavorite('.$exchange->getId().', this); return false;">' . $this->view->iconUnFavorite('удалить из избранного') . '</a>';
        } else {
            $html[] = '<a href="#" onclick="addFavorite('.$exchange->getId().', this); return false;">' . $this->view->iconFavorite('В избранное') . '</a>';
        }

        if (!$exchange->getHide()) {
            $html[] = '<a href="#" onclick="hideFromAnalysis('.$exchange->getId().', this); return false;">' . $this->view->iconDelete('Скрыть') . '</a>';
        } else {
            $html[] = 'этот элемент скрыт';
        }

        return implode('&nbsp;&nbsp;', $html);
    }

}
