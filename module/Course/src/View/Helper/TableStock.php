<?php
namespace Course\View\Helper;

use Zend\View\Helper\AbstractHelper;

class TableStock extends AbstractHelper
{

    public function __invoke(array $params)
    {
        $html = '<table class="table table-bordered" id="analysis-stock">
            <thead>
            <tr>
                <th>Наименование</th>
                <th>Анализ</th>
                <th>Вес</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>';
                if (count($params)) {
                    foreach ($params as $item) {
                        $class = $item['exchange']->getHide() ? 'hidden' : '';
                        $html .= '<tr class="'.$class.'">
                                    <td>
                                        <a href="' . $this->view->url('course', ['action' => 'stock', 'id' => (int)$item['exchange']->getCode()]) .'">
                                                    ' . $this->view->escapeHtml($item['exchange']->getName()) . '
                                        </a><p>'.$item['dateTrade'].'</p>
                                    </td>
                                    <td>' . $this->view->blockAnalysis($item).'</td>
                                    <td>' . ceil($item['weight']) .'</td>
                                    <td>' . $this->view->blockAction($item['exchange']) . '</td>
                                </tr>';
                    }
                } else {
                    $html .= '<tr><td colspan="2">' . $this->view->escapeHtml('Акции не найдены') .'</td></tr>';
                }
                $html .= '
            </tbody>
        </table>';
        return $html;
    }

}
