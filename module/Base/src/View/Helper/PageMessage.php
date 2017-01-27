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
 * $this->pageMessage()->addError('error');
 * $this->pageMessage()->addWarning('warning');
 * $this->pageMessage()->addInfo('info');
 * $this->pageMessage()->addSuccess('success');
 *
 * @package Base\View\Helper
 */
class PageMessage extends AbstractHelper
{

    private $items
        = ['error' => [], 'warning' => [],
           'info'  => [], 'success' => [],];


    public function addError($mess)
    {
        $this->items['error'][] = $mess;
    }

    public function addWarning($mess)
    {
        $this->items['warning'][] = $mess;
    }

    public function addInfo($mess)
    {
        $this->items['info'][] = $mess;
    }

    public function addSuccess($mess)
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