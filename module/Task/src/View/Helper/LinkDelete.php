<?php
namespace Task\View\Helper;

use Task\Entity\Task;
use Zend\View\Helper\AbstractHelper;

class LinkDelete extends AbstractHelper
{
    
    public function __invoke(Task $model, $name='')
    {
        $xhtml = '';
        if ($model->isPercent()) {
            $xhtml .= '<a href="/tasks/percent/delete/id/'.$model->getId().'">'.$this->view->iconDelete($name).' '.$name.'</a>';
        }elseif ($model->isOvertime()) {
            $xhtml .= '<a href="/tasks/overtime/delete/id/'.$model->getId().'">'.$this->view->iconDelete($name).' '.$name.'</a>';
        }
        return $xhtml;
    }
        
}
