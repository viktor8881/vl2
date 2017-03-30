<?php
namespace Base\View\Helper;

use Zend\Form\Element\Button;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Submit;
use Zend\Form\ElementInterface;
use Zend\Form\Form;
use Zend\View\Helper\AbstractHelper;

class FormHelper extends AbstractHelper
{

    const COLUMN_LABEL = '3';


    public function __invoke(Form $form)
    {
        $xhtml = '<div class="row"><div class="col-md-12">';
        $xhtml .= $this->view->form()->openTag($form);

//        pr($form->getElements()); exit;
        /** @var ElementInterface $element */
        foreach ($form->getElements() as $element) {
            $class = (count($element->getMessages())) ? ' has-error' : '';
            $xhtml .= '<div class="form-group' . $class . '">';
            if ($element->getLabel()) {
                $element->setLabelAttributes(
                    ['class' => 'col-sm-4 control-label']
                );
                $xhtml .= $this->view->formLabel($element);
            }
            $xhtml .= '<div class="col-sm-8">';
            switch (get_class($element)) {
                case Button::class:
                case Submit::class:
                    break;
                case MultiCheckbox::class:
                    $xhtml .= '<div class="checkbox">';
                    $element->setLabelAttributes(['class' => 'checkbox']);
                    $xhtml .= $this->view->formElement($element);
                    $xhtml .= $this->view->formElementErrors($element);
                    $xhtml .= '</div>';
                    break;
                default:
                    $element->setAttributes(['class' => 'form-control']);
                    $xhtml .= $this->view->formElement($element);
                    $xhtml .= $this->view->formElementErrors($element);
                    break;
            }
            $xhtml .= '</div>';
            $xhtml .= '</div>';
        }

        $buttonsAction = $form->getButtonsAction();
        if (count($buttonsAction)) {
            $xhtml .= '<div class="form-group"><div class="col-sm-offset-4 col-sm-8">';
            foreach ($buttonsAction as $button) {
                if ($button instanceof Button) {
                    $xhtml .= $this->view->formButton(
                        $button, $button->getValue()
                    );
                } elseif ($button instanceof Submit) {
                    $xhtml .= $this->view->formSubmit($button);
                }
            }
            $xhtml .= '</div></div>';
        }

        $xhtml .= $this->view->form()->closeTag($form);
        $xhtml .= '</div></div>';
        return $xhtml;
    }

}
