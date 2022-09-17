<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DataMapper;

use Linna\TestHelper\DataMapper\DomainObjectMock;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Domain Object Test
 */
class DomainObjectAbstractTest extends TestCase
{
    /** @var DomainObjectMock The domain object class. */
    protected static DomainObjectMock $domainObject;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$domainObject = new DomainObjectMock();
    }

    /**
     * Tear down after class.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        //self::$user = null;
    }

    /**
     * Test create new object instance.
     *
     * @return void
     */
    public function testNewObjectInstance(): void
    {
        $this->assertInstanceOf(DomainObjectMock::class, self::$domainObject);
    }

    /**
     * Test set object id.
     *
     * @return void
     */
    public function testSetObjectId(): void
    {
        $this->assertEquals(null, self::$domainObject->getId());

        self::$domainObject->setId(1);

        $this->assertEquals(1, self::$domainObject->getId());
    }

    /**
     * Test get object id with method.
     *
     * @return void
     */
    public function testGetObjectIdWithMethod(): void
    {
        $this->assertEquals(1, self::$domainObject->getId());
    }

    /**
     * Test get object id with property.
     *
     * @return void
     */
    public function testGetObjectIdWithProperty(): void
    {
        $this->assertEquals(1, self::$domainObject->id);
    }

    /**
     * Test get unknown property.
     *
     * @return void
     */
    public function testGetUnknownProperty(): void
    {
        $this->assertEquals(null, self::$domainObject->unknown);
    }

    /**
     * Test isset property.
     *
     * @return void
     */
    public function testIssetProperty(): void
    {
        $this->assertTrue(isset(self::$domainObject->id));
        $this->assertFalse(isset(self::$domainObject->unknown));
    }

    /**
     * Test override object id.
     *
     * @return void
     */
    public function testOverrideObjectId(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("ObjectId property is immutable.");

        self::$domainObject->setId(1);
        self::$domainObject->setId(2);
    }
}
