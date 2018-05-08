<?php
namespace Cron\Controller;

use Course\Service\MoexService;
use Zend\Mvc\Controller\AbstractActionController;

class MoexController extends AbstractActionController
{

    /** @var MoexService */
    private $moexService;

    /**
     * MoexController constructor.
     *
     * @param MoexService $moexService
     */
    public function __construct(MoexService $moexService)
    {
        $this->moexService = $moexService;
    }


    public function indexAction()
    {
        $moexRepository = $this->moexService->receiveLast();

        if ($moexRepository->count() && !$this->moexService->hasByDate($moexRepository->getTradeDateTime())) {
            try {
                $this->moexService->insertCollection($moexRepository);
                echo 'insert row ' . $moexRepository->count();
            } catch (\Exception $exception) {
                $this->getResponse()->setStatusCode(500);
                return $this->getResponse();
            }
        } else {
            echo 'insert row 0';
            $this->getResponse()->setStatusCode(412);
        }
        return $this->getResponse();
    }


}
