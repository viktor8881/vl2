<?php
namespace Cron\Service;

interface MessageInterface
{

    public function getAnalyzesOvertimeTask();

    public function getAnalyzesPercentTask();

    public function getListAnalyzesFigureTask();

    public function getStatusCross();

    public function getSrcGraph();

    public function getSubject();

}
