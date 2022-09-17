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

use Linna\TestHelper\DataMapper\UuidDomainObjectMock;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Domain Object Test
 */
class UuidDomainObjectAbstractTest extends TestCase
{
    /** @var UuidDomainObjectMock The domain object class. */
    protected static UuidDomainObjectMock $domainObject;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$domainObject = new UuidDomainObjectMock();
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
        $this->assertInstanceOf(UuidDomainObjectMock::class, self::$domainObject);
    }

    /**
     * Test set object id.
     *
     * @return void
     */
    public function testSetObjectId(): void
    {
        self::$domainObject->setId('a93a806d-f66f-4a23-a8d6-00e3f0ab20be');

        $this->assertEquals('a93a806d-f66f-4a23-a8d6-00e3f0ab20be', self::$domainObject->getId());
    }

    /**
     * Test get object id with method.
     *
     * @return void
     */
    public function testGetObjectIdWithMethod(): void
    {
        $this->assertEquals('a93a806d-f66f-4a23-a8d6-00e3f0ab20be', self::$domainObject->getId());
    }

    /**
     * Test get object id with property.
     *
     * @return void
     */
    public function testGetObjectIdWithProperty(): void
    {
        $this->assertEquals('a93a806d-f66f-4a23-a8d6-00e3f0ab20be', self::$domainObject->id);
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

        self::$domainObject->setId('a93a806d-f66f-4a23-a8d6-00e3f0ab20be');
        self::$domainObject->setId('cb116b04-dad1-4d6d-b3fe-5f97373ab868');
    }
}
