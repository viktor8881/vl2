<?php

namespace AnalysisTest\Service;


use Analysis\Service\MovingAverage;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase as TestCase;

class MovingAverageTest extends TestCase
{

    const DURRING_DEFAULT = 3;

    /** @var MovingAverage */
    private $serviceTest;


    public function setUp() {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $this->serviceTest = $this->getApplicationServiceLocator()->get(MovingAverage::class);
    }

    /**
     * @dataProvider additionListAvg
     */
    public function testListAvg($courses, $result)
    {
//        $this->markTestSkipped('dont work');
        $actual = $this->serviceTest->listAvg($courses, self::DURRING_DEFAULT);
//        echo "result \n"; print_r($result); echo "\n";
//        echo "actual \n"; print_r($actual); echo "\n";
//        print_r('========================='."\n");
//        die('asd');
        $this->assertEquals($result, $actual);
    }

    public function additionListAvg() {
        return [
            [[1,2,3], [2,2,2]],
            [[1,2,3,4], [2,2,2,3]],
            [[1,2,3,4,5], [2,2,2,3,4]],
            [[1,2,3,4,5,6], [2,2,2,3,4,5]],
            [[1,2,3,4,5,6,7], [2,2,2,3,4,5,6]],
            [[1,2,3,4,5,6,7,8], [2,2,2,3,4,5,6,7]],
            [[1,2,3,4,5,6,7,8,9], [2,2,2,3,4,5,6,7,8]],

            [[9,8,7,6,5,4,3,2,1], [8,8,8,7,6,5,4,3,2]],
            [[9,8,7,6,5,4,3,2], [8,8,8,7,6,5,4,3]],
            [[9,8,7,6,5,4,3], [8,8,8,7,6,5,4]],
            [[9,8,7,6,5,4], [8,8,8,7,6,5]],
            [[9,8,7,6,5], [8,8,8,7,6]],
            [[9,8,7,6], [8,8,8,7]],
            [[9,8,7], [8,8,8]]
        ];
    }

}