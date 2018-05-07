<?php
namespace Analysis\Entity;


interface FigureAnalysisInterface
{

    public function getId();

    public function getExchange();

    public function getFigure();

    public function getCacheCourses();

    public function getCreated();

    public function getFirstDate();

    public function getLastDate();

    public function getPercentCacheCourses();

}
