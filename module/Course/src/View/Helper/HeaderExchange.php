<?php

namespace Course\View\Helper;

use Exchange\Entity\Exchange;
use Zend\View\Helper\AbstractHelper;

class HeaderExchange extends AbstractHelper
{

    public function __invoke(array $exchanges, Exchange $currentExchange,
        array $period
    ) {
        $xhtml = '<h3>';
        $xhtml .= _('Курс') . ' ';
        $xhtml .= '<form method="get" action="" class="form-inline" style="display:inline">';
        $xhtml .= ' <div class="form-group"><label for="metalName" class="sr-only">'
            . _('Метал') . '</label>'
            . '<select name="metalName" id="metalName" class="form-control">';
        foreach ($exchanges as $exchange) {
            if ($exchange->getId() == $currentExchange->getId()) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            $xhtml .= '<option value="' . $exchange->getId() . '" ' . $selected
                . '>' . $this->view->escapeHtml($exchange->getName())
                . '</option>';
        }
        $xhtml .= '</select></div>';
        $xhtml .= ' <div class="form-group">'
            . '<label for="daterange" class="sr-only">' . _('Период')
            . '</label>'
            . '<input type="text" class="form-control" name="daterange" id="daterange" value="">'
            . '</div>';
        $xhtml .= ' <button type="button" class="btn btn-success" id="btn-filter">'
            . _('Применить') . '</button>';
        $xhtml .= '</form>';
        $xhtml .= '</h3>';

        $url = $this->view->url(
            'course',
            ['action' => $currentExchange->isMetal() ? 'metal' : 'currency']
        );

        $this->view->headScript()->prependFile(
            '//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js'
        );
        $this->view->headScript()->prependFile(
            '//cdn.jsdelivr.net/momentjs/latest/moment.min.js'
        );
        $this->view->headLink()->appendStylesheet(
            '//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css'
        );
        $this->view->headScript()->captureStart();
        echo
            "$(function(){"
            . "$('input[name=\"daterange\"]').daterangepicker(
            {
                
                locale: {
                    language: 'ru',
                  format: 'DD.MM.YYYY'
                },
                startDate: '" . $period['start'] . "',
                endDate: '" . $period['end'] . "'
            });
            
            $('#btn-filter').click(function() {
                var splitdr = $('#daterange').val().split(' - ');
                var url = '" . $url . "/'+$('#metalName').val()+'?start='+splitdr[0]+'&end='+splitdr[1];
                window.location = url;
            });
        });";
        $this->view->headScript()->captureEnd();

        return $xhtml;
    }

}