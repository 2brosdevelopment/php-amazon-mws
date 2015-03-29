<?php namespace TwoBrosDevTest\Report;

use TwoBrosDev\Report\AmazonReportScheduleManager;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-12-12 at 13:17:14.
 */
class AmazonReportScheduleManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AmazonReportScheduleManager
     */
    protected $object;

    public function testSetReportType()
    {

        $this->assertFalse( $this->object->setReportType( null ) ); //can't be nothing
        $this->assertFalse( $this->object->setReportType( 5 ) ); //can't be an int
        $this->assertNull( $this->object->setReportType( '_GET_ORDERS_DATA_' ) );
        $o = $this->object->getOptions();
        $this->assertArrayHasKey( 'ReportType', $o );
        $this->assertEquals( '_GET_ORDERS_DATA_', $o[ 'ReportType' ] );
    }

    public function testSetSchedule()
    {

        $this->assertFalse( $this->object->setSchedule( null ) ); //can't be nothing
        $this->assertFalse( $this->object->setSchedule( 5 ) ); //can't be an int
        $this->assertNull( $this->object->setSchedule( '_30_DAYS_' ) );
        $o = $this->object->getOptions();
        $this->assertArrayHasKey( 'Schedule', $o );
        $this->assertEquals( '_30_DAYS_', $o[ 'Schedule' ] );
    }

    public function testSetScheduledDate()
    {

        $this->assertNull( $this->object->setScheduledDate( null ) ); //default

        $this->assertNull( $this->object->setScheduledDate( '-1 min' ) );
        $o = $this->object->getOptions();
        $this->assertArrayHasKey( 'ScheduledDate', $o );
        $this->assertStringMatchesFormat( '%d-%d-%dT%d:%d:%d%i', $o[ 'ScheduledDate' ] );
        $this->assertNotEquals( '1969-12-31T18:58:00-0500', $o[ 'ScheduledDate' ] );
    }

    public function testManageReportSchedule()
    {

        resetLog();
        $this->object->setMock( true, 'manageReportSchedule.xml' );
        $this->assertFalse( $this->object->manageReportSchedule() ); //no report type yet
        $this->object->setReportType( '_GET_ORDERS_DATA_' );

        $this->assertFalse( $this->object->manageReportSchedule() ); //no report schedule yet
        $this->object->setSchedule( '_30_DAYS_' );

        $this->assertNull( $this->object->manageReportSchedule() );

        $o = $this->object->getOptions();
        $this->assertEquals( 'ManageReportSchedule', $o[ 'Action' ] );

        $check = parseLog();
        $this->assertEquals( 'Single Mock File set: manageReportSchedule.xml', $check[ 1 ] );
        $this->assertEquals( 'Report Type must be set in order to manage a report schedule!', $check[ 2 ] );
        $this->assertEquals( 'Schedule must be set in order to manage a report schedule!', $check[ 3 ] );
        $this->assertEquals( 'Fetched Mock File: test/mock/manageReportSchedule.xml', $check[ 4 ] );

        return $this->object;
    }

    /**
     * @depends testManageReportSchedule
     */
    public function testGetReportType( $o )
    {

        $get = $o->getReportType( 0 );
        $this->assertEquals( '_GET_ORDERS_DATA_', $get );

        $this->assertFalse( $o->getReportType( 'wrong' ) ); //not number
        $this->assertFalse( $o->getReportType( 1.5 ) ); //not integer
        $this->assertFalse( $this->object->getReportType() ); //not fetched yet for this object
    }

    /**
     * @depends testManageReportSchedule
     */
    public function testGetSchedule( $o )
    {

        $get = $o->getSchedule( 0 );
        $this->assertEquals( '_30_DAYS_', $get );

        $this->assertFalse( $o->getSchedule( 'wrong' ) ); //not number
        $this->assertFalse( $o->getSchedule( 1.5 ) ); //not integer
        $this->assertFalse( $this->object->getSchedule() ); //not fetched yet for this object
    }

    /**
     * @depends testManageReportSchedule
     */
    public function testGetScheduledDate( $o )
    {

        $get = $o->getScheduledDate( 0 );
        $this->assertEquals( '2009-02-20T02:10:42+00:00', $get );

        $this->assertFalse( $o->getScheduledDate( 'wrong' ) ); //not number
        $this->assertFalse( $o->getScheduledDate( 1.5 ) ); //not integer
        $this->assertFalse( $this->object->getScheduledDate() ); //not fetched yet for this object
    }

    /**
     * @depends testManageReportSchedule
     */
    public function testGetList( $o )
    {

        $x                     = [ ];
        $x1                    = [ ];
        $x1[ 'ReportType' ]    = '_GET_ORDERS_DATA_';
        $x1[ 'Schedule' ]      = '_30_DAYS_';
        $x1[ 'ScheduledDate' ] = '2009-02-20T02:10:42+00:00';
        $x[ 0 ]                = $x1;

        $this->assertEquals( $x, $o->getList() );
        $this->assertEquals( $x1, $o->getList( 0 ) );

        $this->assertFalse( $this->object->getList() ); //not fetched yet for this object
    }

    /**
     * @depends testManageReportSchedule
     */
    public function testGetCount( $o )
    {

        $get = $o->getCount();
        $this->assertEquals( '1', $get );

        $this->assertFalse( $this->object->getCount() ); //not fetched yet for this object
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

        setupDummyConfigFile();
        resetLog();
        $this->object = new AmazonReportScheduleManager( 'testStore', true, null, __DIR__ . '/../test-config.php' );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

        removeDummyConfigFile();
    }

}

require_once( 'test/helperFunctions.php' );