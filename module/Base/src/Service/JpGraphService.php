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


    public function generateGraphByParams(array $dataBase, array $dataAvg1, array $dataAvg2, array $dataLabels, $width=500, $height=250)
    {
        // Setup the graph
        $graph = new \Graph($width, $height);
        $graph->SetScale("textlin");

        $theme_class=new \UniversalTheme();

        $graph->SetTheme($theme_class);
        $graph->img->SetAntiAliasing(false);
        $graph->title->Set(' ');
        $graph->SetBox(false);

        $graph->img->SetAntiAliasing();

        $graph->yaxis->HideZeroLabel();
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
//        $graph->xaxis->SetTickLabels(array('01.02','02.02','03.02','04.02'));
//        $graph->xaxis->SetTickLabels($dataLabels);
        $graph->xaxis->HideLabels();
//        $graph->xaxis->SetLabelAngle(45);
        $graph->xgrid->SetColor('#E3E3E3');

// Create the first line
        $p1 = new \LinePlot($dataBase);
        $graph->Add($p1);
        $p1->SetColor("#6495ED");
        $p1->SetLegend('Course');

// Create the second line
        $p2 = new \LinePlot($dataAvg1);
        $graph->Add($p2);
        $p2->SetColor("#B22222");
        $p2->SetLegend('avg - 9');

// Create the third line
        $p3 = new \LinePlot($dataAvg2);
        $graph->Add($p3);
        $p3->SetColor("#FF1493");
        $p3->SetLegend('avg - 14');

        $graph->legend->SetFrameWeight(1);
//        $graph->legend->SetPos(0,0.2,'right','center');
//        $graph->legend->SetColumns(1);

//        $graph->SetMargin(40,100,0,0);

        $prefixFolder = date('Y');
        if (!file_exists($this->folderImgs . $prefixFolder)) {
            mkdir($this->folderImgs . $prefixFolder);
        }

        $fileName = $prefixFolder . '/' . rand(0, 50) . uniqid(time()) .'.png';
        $graph->Stroke($this->folderImgs . $fileName);
        return $this->publicPath . $fileName;
    }

}
