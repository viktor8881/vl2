<?php
namespace Cron\Service;


use Exchange\Entity\Exchange;

interface MessageInterface
{

    public function getAnalyzesOvertimeTask();

    public function getAnalyzesPercentTask();

    public function getListAnalyzesFigureTask();

    public function getStatusCross();

    public function getSrcGraph();
    
}
