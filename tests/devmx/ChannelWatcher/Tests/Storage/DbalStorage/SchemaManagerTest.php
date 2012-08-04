<?php

namespace devmx\ChannelWatcher\Tests\Storage\DbalStorage;

use devmx\ChannelWatcher\Storage\DbalStorage\SchemaManager;
use Doctrine\Tests\TestUtil;


/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-08-04 at 17:02:52.
 */
class SchemaManagerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var SchemaManager
     */
    protected $mockedManager;
    
    protected $connectionMock;
    
    protected $dbManager;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->connectionMock = $this->getMockBuilder('\Doctrine\DBAL\Connection')
                                     ->disableOriginalConstructor()
                                     ->disableOriginalClone()
                                     ->getMock();
        $this->mockedManager = new SchemaManager($this->connectionMock, 'foo');
    }
    

    /**
     * @covers devmx\ChannelWatcher\Storage\DbalStorage\SchemaManager::createTables
     * @covers devmx\ChannelWatcher\Storage\DbalStorage\SchemaManager::getMigrateStatements
     * @covers devmx\ChannelWatcher\Storage\DbalStorage\SchemaManager::__construct
     * @covers devmx\ChannelWatcher\Storage\DbalStorage\SchemaManager::getSchema
     */
    public function testCreateTables() {
        $expectedSchema = SchemaManager::getSchema('foo_channels', 'foo_crawl_data');
                
        $conn = TestUtil::getConnection();
        $manager = new SchemaManager($conn, 'foo_');
        $manager->createTables();
        $createdSchema = $conn->getSchemaManager()->createSchema();
        
        //fix differences in handling autoincrement        
        if($conn->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\SqlitePlatform) {
            $expectedSchema->getTable('foo_crawl_data')
                           ->getColumn('id')
                           ->setAutoincrement(false);
        }
        
        if($conn->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSqlPlatform) {
            $expectedSchema->createSequence('foo_crawl_data_id_seq');
        }
        
        $diff = \Doctrine\DBAL\Schema\Comparator::compareSchemas($createdSchema, $expectedSchema);
       
        $emptyDiff = new \Doctrine\DBAL\Schema\SchemaDiff();
                
        $this->assertEquals($emptyDiff, $diff);
    }

    /**
     * @covers devmx\ChannelWatcher\Storage\DbalStorage\SchemaManager::getChannelTableName
     */
    public function testGetChannelTableName() {
        $this->connectionMock->expects($this->once())
                             ->method('quoteIdentifier')
                             ->with($this->equalTo('foochannels'))
                             ->will($this->returnValue('escaped_name'));
        $this->assertEquals('escaped_name', $this->mockedManager->getChannelTableName());
    }

    /**
     * @covers devmx\ChannelWatcher\Storage\DbalStorage\SchemaManager::getCrawlDateTableName
     */
    public function testGetCrawlDateTableName() {
        $this->connectionMock->expects($this->once())
                             ->method('quoteIdentifier')
                             ->with($this->equalTo('foocrawl_data'))
                             ->will($this->returnValue('escaped_name'));
        $this->assertEquals('escaped_name', $this->mockedManager->getCrawlDateTableName());
    }

}
