<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Course\Service;


use Course\Entity\Course;
use Exchange\Entity\Exchange;

class CourseService
{
    const URL_CURRENCY_COURSES = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';
    const URL_METAL_COURSES = 'http://www.cbr.ru/scripts/xml_metall.asp?date_req1=%date%&date_req2=%date%';

    /** @var CourseManager */
    private $courseManager;

    /**
     * @param CourseManager $courseManager
     */
    public function __construct(CourseManager $courseManager)
    {
        $this->courseManager = $courseManager;
    }

    /**
     * @param \DateTime  $date
     * @param Exchange[] $exchanges
     *
     * @return Course[]
     */
    public function receiveByDateToListCourse(\DateTime $date, array $exchanges)
    {
        $listExchange = [];
        foreach ($exchanges as $exchange) {
            $listExchange[$exchange->getCode()] = $exchange;
        }

        $listCourse = [];
        $receivedData = $this->receiveByDateToArray($date);
        if (count($receivedData)) {
            foreach ($receivedData as $code => $item) {
                if (isset($listExchange[$code])) {
                    /** @var Course $course */
                    $course = $this->courseManager->createEntity();
                    $course->setExchange($listExchange[$code])
                        ->setNominal($item['nominal'])
                        ->setSell($item['value'])
                        ->setBuy($item['value'])
                        ->setDateCreate($date);
                    $listCourse[] = $course;
                }
            }
        }
        return $listCourse;
    }


    /**
     * @param \DateTime $date
     * @return array
     */
    private function receiveByDateToArray(\DateTime $date)
    {
        $result = [];
        $xmlstr = file_get_contents(self::URL_CURRENCY_COURSES . $date->format('d/m/Y'));
        $simpleXml = new \SimpleXMLElement($xmlstr);
        if (false !== strstr($xmlstr, $date->format('d.m.Y'))) {
            foreach ($simpleXml->Valute as $item) {
                $result[(string)$item['ID']] = [
                    'value'   => str_replace(',', '.', (string)$item->Value),
                    'nominal' => str_replace(',', '.', (string)$item->Nominal)];
            }
        }

        $xmlstr = file_get_contents(
            str_replace('%date%', $date->format('d/m/Y'), self::URL_METAL_COURSES)
        );
        $simpleXml = new \SimpleXMLElement($xmlstr);
        if (false !== strstr($xmlstr, $date->format('d.m.Y'))) {
            foreach ($simpleXml->Record as $item) {
                $result[(string)$item['Code']] = [
                    'value'   => str_replace(',', '.', (string)$item->Buy),
                    'nominal' => 1];
            }
        }
        return $result;
    }

}