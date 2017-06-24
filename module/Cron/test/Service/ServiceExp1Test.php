<?php

namespace AnalysisTest\Service;

/**
 * Description of Service_GraphAnalysis
 *
 * @author Viktor
 */
use Course\Entity\Course;
use Course\Entity\CourseCollection;
use Cron\Service\ServiceExp1;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase as TestCase;


class ServiceExp1Test extends TestCase {

    /** @var ServiceExp1 */
    private $serviceTest;
    /** @var CourseCollection */
    private $coll;
    /** @var Course[] */
    private $coursesArray = [];


    public function setUp() {
        $configOverrides = [];
        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $this->serviceTest = $this->getApplicationServiceLocator()->get(ServiceExp1::class);;

        $data = json_decode(file_get_contents('module/Cron/test/data/courses.json'), true);
        foreach ($data as $value) {
            $course = new Course();
            $course->setId($value['id'])
                ->setBuy($value['buy'])
                ->setSell($value['sell'])
                ->setNominal($value['nominal'])
                ->setDateCreate(new \DateTime($value['dateCreate']));
            $this->coursesArray[] = $course;
        }
    }


    public function testCountCourses()
    {
        $this->serviceTest->init(new CourseCollection($this->coursesArray));
        $this->assertEquals(count($this->coursesArray), $this->serviceTest->countCourses());
        $this->assertEquals(count($this->coursesArray), $this->serviceTest->countTrend());
    }


    public function testIsDownTrendTrue()
    {
        $this->serviceTest->init(new CourseCollection($this->coursesArray));
        $this->assertTrue($this->serviceTest->isDownTrend());
    }

    public function testIsDownTrendFalse()
    {
        $this->serviceTest->init(new CourseCollection(array_reverse($this->coursesArray)));
        $this->assertFalse($this->serviceTest->isDownTrend());
    }

//    public function testCountLastDownTrend()
//    {
//        $this->serviceTest->init(new CourseCollection($this->coursesArray));
//        $this->assertEquals(1, $this->serviceTest->countLastDownTrend());
//    }

    public function testIsUpTrendFalse()
    {
        $this->serviceTest->init(new CourseCollection($this->coursesArray));
        $this->assertFalse($this->serviceTest->isUpTrend());
    }

    public function testCountLastDownTrend2()
    {
        $this->serviceTest->init(new CourseCollection(array_reverse($this->coursesArray)));
        $this->assertEquals(0, $this->serviceTest->countLastDownTrend());
    }

    public function testGetLastCourseValue()
    {
        $this->serviceTest->init(new CourseCollection($this->coursesArray));
        $this->assertEquals(end($this->coursesArray)->getValue(), $this->serviceTest->getLastCourseValue());
    }

    public function testGetLastTrendValue()
    {
        $this->serviceTest->init(new CourseCollection($this->coursesArray));
        $this->assertEquals(2285.6478571429, $this->serviceTest->getLastTrendValue());
    }


    public function testGetLast7ValuesChangeTrend()
    {
        $this->serviceTest->init(new CourseCollection($this->coursesArray));
        $expected = ['22.03.2017' => 2267.050000,
            '23.03.2017' => 2309.070000,
            '25.03.2017' => 2296.730000,
            '28.03.2017' => 2304.330000,
            '29.03.2017' => 2294.860000,
            '30.03.2017' => 2297.020000,
            '01.04.2017' => 2234.030000];
        $this->assertEquals($expected, $this->serviceTest->getLast7ValuesChangeTrend());
    }

    public function testIsCrossUpTrendTrue()
    {
        $this->serviceTest->init(new CourseCollection($this->coursesArray));
        $values = ['22.03.2017' => 2267.050000,
                     '23.03.2017' => 2309.070000,
                     '25.03.2017' => 2296.730000,
                     '28.03.2017' => 2304.330000,
                     '29.03.2017' => 2294.860000,
                     '30.03.2017' => 2297.020000,
                     '01.04.2017' => 2234.030000];
        $this->assertTrue($this->serviceTest->isCrossUpTrend($values));
    }

    public function testIsCrossUpTrendFalse()
    {
        $this->serviceTest->init(new CourseCollection($this->coursesArray));
        $values = ['22.03.2017' => 2267.050000,
                   '23.03.2017' => 2309.070000,
                   '25.03.2017' => 2296.730000,
                   '28.03.2017' => 2304.330000,
                   '29.03.2017' => 2294.860000,
                   '30.03.2017' => 2297.020000,
                   '01.04.2017' => 2234.030000];
        $this->assertFalse($this->serviceTest->isCrossUpTrend($values,0.67));
    }

    public function testIsDoubleBottomTrue()
    {
        $this->serviceTest->init(new CourseCollection($this->coursesArray));
        $values = ['22.03.2017' => 374.24,
                   '23.03.2017' => 373.83,
                   '25.03.2017' => 365.79,
                   '28.03.2017' => 366.70,
                   '29.03.2017' => 367.83];

        $this->assertFalse($this->serviceTest->isDoubleBottom($values));
    }
}

