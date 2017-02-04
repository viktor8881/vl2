<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class PageMessage
 *
 * In Controller through flashMessenger
 * $this->flashMessenger()->addErrorMessage('Sorry, the old password is incorrect. Could not set the new password.');
 * $this->flashMessenger()->addSuccessMessage('Changed the password successfully.');
 * $this->flashMessenger()->addWarningMessage('Changed the password successfully.');
 * $this->flashMessenger()->addInfoMessage('INFO the password successfully.');
 *
 * In View through this class
 * $this->pageMessage()->addErrorMessage('error');
 * $this->pageMessage()->addWarningMessage('warning');
 * $this->pageMessage()->addInfoMessage('info');
 * $this->pageMessage()->addSuccessMessage('success');
 *
 * @package Base\View\Helper
 */
class PageMessage extends AbstractHelper
{

    private $items
        = ['error' => [], 'warning' => [],
           'info'  => [], 'success' => [],];


    public function addErrorMessage($mess)
    {
        $this->items['error'][] = $mess;
    }

    public function addWarningMessage($mess)
    {
        $this->items['warning'][] = $mess;
    }

    public function addInfoMessage($mess)
    {
        $this->items['info'][] = $mess;
    }

    public function addSuccessMessage($mess)
    {
        $this->items['success'][] = $mess;
    }


    public function render()
    {
        $xhtml = '';
        foreach ($this->items as $key => $messages) {
            $flashMessages = $this->view->flashMessenger()->render(
                $key, ['list-unstyled']
            );
            $countViewMessages = count($messages);
            if (strlen($flashMessages) || $countViewMessages) {
                $content = $flashMessages;
                if ($countViewMessages) {
                    $content .= '<ul class="list-unstyled">';
                    foreach ($messages as $mess) {
                        $content .= '<li>' . $this->view->escapeHtml($mess)
                            . '</li>';
                    }
                    $content .= '</ul>';
                }
                $xhtml .= $this->renderItem($key, $content);
            }
            $xhtml .= '';
        }
        return $xhtml;
    }

    protected function renderItem($type, $content)
    {
        $class = $type == 'error' ? 'danger' : $type;
        return '<div class="alert alert-' . $class . '" role="alert">'
            . $content . '</div>';
    }

}