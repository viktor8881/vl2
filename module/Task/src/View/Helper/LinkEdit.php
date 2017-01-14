<?php
namespace Task\View\Helper;

use Task\Entity\Task;
use Zend\View\Helper\AbstractHelper;

class LinkEdit extends AbstractHelper
{
    public function __invoke(Task $model, $name='')
    {
        $xhtml = '';
        if ($model->isPercent()) {
            $xhtml .= '<a href="/tasks/percent/edit/id/'.$model->getId().'">'.$this->view->iconEdit($name).' '._($name).'</a>';
        }elseif ($model->isOvertime()) {
            $xhtml .= '<a href="/tasks/overtime/edit/id/'.$model->getId().'">'.$this->view->iconEdit($name).' '._($name).'</a>';
        }
        return $xhtml;
    }
        
}
