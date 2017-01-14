<?php
namespace Task\View\Helper;

use Exchange\Entity\Exchange;
use Task\Entity\Task;
use Zend\View\Helper\AbstractHelper;

class Name extends AbstractHelper
{

    public function __invoke(Task $model)
    {
        $xhtml = '<div class="row">';
        if ($model->isModeOnlyUp()) {
            $modeName = _('Рост');
        }elseif($model->isModeOnlyDown()) {
            $modeName = _('Понижение');
        }else{
            $modeName = _('Pост/понижение');
        }
        if ($model->isPercent()) {
            $xhtml .= '<div class="col-sm-12">'.sprintf(_('%1$s на %2$s за %3$s'), $modeName, $this->view->formatPercent($model->getPercent(), true), $this->view->pluralDays($model->getPercent(), true)).'</div>';
        }elseif ($model->isOvertime()) {
            $xhtml .= '<div class="col-sm-12">'.sprintf(_('%1$s в течении %2$s'), $modeName, $this->view->pluralDaysGenitive($model->getPercent(), true)).'</div>';
        }
        $listMetal = $model->getListMetal();
        if (count($listMetal)) {
            $xhtml .= '<div class="col-sm-3 text-success" style="padding-top:16px; padding-bottom:16px;">'
                . '<strong>'._('Металы').' </strong>'
                . '<a href="#" data-toggle="tooltip" label label-warning title="'.$this->view->namesExchange($listMetal).'">'
                . '<span class="label label-success">'.count($listMetal).'</span>'
                .'</a>'
                . '</div>';
        }
        $listCurrency = $model->getListCurrency();
        if (count($listCurrency)) {
            $xhtml .= '<div class="col-sm-3 text-warning" style="padding-top:16px; padding-bottom:16px;">'
                . '<strong>'._('Валюты').'</strong> '
                . '<a href="#" data-toggle="tooltip" label label-warning title="'.$this->view->namesExchange($listCurrency).'">'
                . '<span class="label label-warning">'.count($listCurrency).'</span>'
                .'</a>'
                . '</div>';
        }
        $xhtml .= '</div>';
        return $xhtml;
    }

}