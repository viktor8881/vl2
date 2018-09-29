<?php
namespace Base\Service;

require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');

class JpGraphService
{

    private $folderImgs;
    private $publicPath;

    public function __construct($folderImgs, $publicPath)
    {
        $this->folderImgs = $folderImgs;
        $this->publicPath = $publicPath;
    }


    public function generateGraphByParams(array $dataBase, array $dataAvg1, array $dataAvg2, array $dataLabels, $width=650, $height=350)
    {
        // Setup the graph
        $graph = new \Graph($width, $height);
        $graph->SetMargin(40,20,40,90);
        $graph->SetScale("intlin", 0, 0, reset($dataLabels), end($dataLabels));

        $graph->xaxis->SetLabelFormatCallback( function ($aVal) {return Date('d.m H:i', $aVal);} );
        $graph->xaxis->SetLabelAngle(90);
        $graph->xgrid->Show();
        // Set the labels every 5min (i.e. 2400seconds) and minor ticks every minute
        // установить периодичность здесь
        $graph->xaxis->scale->ticks->Set(480000,60);

        // Create the first line
        $p1 = new \LinePlot($dataBase, $dataLabels);
        $graph->Add($p1);
        $p1->SetColor("#6495ED");
        $p1->SetLegend('Course');

        // Create the second line
        $p2 = new \LinePlot($dataAvg1, $dataLabels);
        $graph->Add($p2);
        $p2->SetColor("#B22222");
        $p2->SetLegend('avg - 9');

        // Create the third line
        $p3 = new \LinePlot($dataAvg2, $dataLabels);
        $graph->Add($p3);
        $p3->SetColor("#FF1493");
        $p3->SetLegend('avg - 14');

        $graph->legend->SetPos(0.6,0.03,'left  ','top');

        $prefixFolder = date('Y');
        if (!file_exists($this->folderImgs . $prefixFolder)) {
            mkdir($this->folderImgs . $prefixFolder);
        }

        $fileName = $prefixFolder . '/' . rand(0, 50) . uniqid(time()) .'.png';
        $graph->Stroke($this->folderImgs . $fileName);
        return $this->publicPath . $fileName;
    }

}
