<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\Metal;


class MetalController extends AbstractActionController
{

    private $serviceMetal;

    public function __construct(Metal $serviceMetal)
    {
        $this->serviceMetal = $serviceMetal;
    }

    public function listAction()
    {
        return new ViewModel(['items' => $this->serviceMetal->fetchAll()]);
    }

}
