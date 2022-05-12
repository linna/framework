<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DataMapper;

//use Linna\DataMapper\NullDomainObject;
use Linna\TestHelper\DataMapper\MapperMock;
use Linna\TestHelper\DataMapper\DomainObjectMock;
use PHPUnit\Framework\TestCase;

/**
 * Mapper Abstract Test
 *
 */
class MapperAbstractTest extends TestCase
{
    /**
     * @var MapperMock The mapper mock
     */
    protected static MapperMock $mapperMock;

    /**
     * Set up before class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$mapperMock = new MapperMock();
    }

    /**
     * Test create new object instance.
     *
     * @return void
     */
    public function testNewObjectInstance(): void
    {
        $this->assertInstanceOf(MapperMock::class, self::$mapperMock);
    }

    /**
     * Test create new domain object with mapper.
     *
     * @return void
     */
    public function testCreateDomainObjectWithMapper(): void
    {
        $this->assertInstanceOf(DomainObjectMock::class, self::$mapperMock->create());
    }

    /**
     * Test save domain object with mapper.
     *
     * @return void
     */
    public function testSaveDomainObjectWithMapper(): void
    {
        //create a new object
        /** @var DomainObjectMock Domain object mock class. */
        $domainObject = self::$mapperMock->create();
        $domainObject->name = 'FooName';
        $domainObject->propertyOne = 'Foo';
        $domainObject->propertyTwo = 'Bar';
        $domainObject->propertyThree = 'Baz';

        //check for effective new object
        $this->assertSame(-1, $domainObject->id);

        //save object
        self::$mapperMock->save($domainObject);

        //check for effective insertion in storage
        $this->assertSame(1, $domainObject->id);
        $this->assertIsNumeric($domainObject->id);

        //retrive object
        /** @var DomainObjectMock Domain object mock class. */
        $savedDomainObject = self::$mapperMock->fetchByName('FooName');

        //check if the object is the same
        $this->assertSame(1, $savedDomainObject->id);
        $this->assertSame('FooName', $savedDomainObject->name);

        //cleaning
        self::$mapperMock->delete($savedDomainObject);

        //check cleaning
        $this->assertInstanceOf(NullDomainObject::class, $savedDomainObject);
    }

    /**
     * Test update domain object with mapper.
     *
     * @return void
     */
    public function testUpdateDomainObjectWithMapper(): void
    {
        //create a new object
        /** @var DomainObjectMock Domain object mock class. */
        $domainObject = self::$mapperMock->create();
        $domainObject->name = 'FooName';
        $domainObject->propertyOne = 'Foo';
        $domainObject->propertyTwo = 'Bar';
        $domainObject->propertyThree = 'Baz';

        //check for effective new object
        $this->assertSame(-1, $domainObject->id);

        //save object
        self::$mapperMock->save($domainObject);

        //check for effective insertion in storage
        $this->assertSame(1, $domainObject->id);
        $this->assertIsNumeric($domainObject->id);

        //retrive object
        /** @var DomainObjectMock Domain object mock class. */
        $savedDomainObject = self::$mapperMock->fetchByName('FooName');

        //check if the object is the same
        $this->assertSame(1, $savedDomainObject->id);
        $this->assertSame('FooName', $savedDomainObject->name);

        //update object property
        $savedDomainObject->name = 'UpdatedFooName';

        //update object
        self::$mapperMock->save($savedDomainObject);

        //retrive updated object
        /** @var DomainObjectMock Domain object mock class. */
        $updatedDomainObject = self::$mapperMock->fetchByName('UpdatedFooName');

        //check if the object is the same
        $this->assertSame(1, $updatedDomainObject->id);
        $this->assertSame('UpdatedFooName', $updatedDomainObject->name);

        //cleaning
        self::$mapperMock->delete($updatedDomainObject);

        //check cleaning
        $this->assertInstanceOf(NullDomainObject::class, $updatedDomainObject);
    }

    /**
     * Test delete domain object with mapper.
     *
     * @return void
     */
    public function testDeleteDomainObjectWithMapper(): void
    {
        //create a new object
        /** @var DomainObjectMock Domain object mock class. */
        $domainObject = self::$mapperMock->create();
        $domainObject->name = 'FooName';
        $domainObject->propertyOne = 'Foo';
        $domainObject->propertyTwo = 'Bar';
        $domainObject->propertyThree = 'Baz';

        //check for effective new object
        $this->assertSame(-1, $domainObject->id);

        //save object
        self::$mapperMock->save($domainObject);

        //check for effective insertion in storage
        $this->assertSame(1, $domainObject->id);
        $this->assertIsNumeric($domainObject->id);

        //retrive object
        /** @var DomainObjectMock Domain object mock class. */
        $savedDomainObject = self::$mapperMock->fetchByName('FooName');

        //check if the object is the same
        $this->assertSame(1, $savedDomainObject->id);
        $this->assertSame('FooName', $savedDomainObject->name);

        //cleaning
        self::$mapperMock->delete($savedDomainObject);

        //check cleaning
        $this->assertInstanceOf(NullDomainObject::class, $savedDomainObject);
        $this->assertInstanceOf(NullDomainObject::class, self::$mapperMock->fetchByName('FooName'));
        $this->assertInstanceOf(NullDomainObject::class, self::$mapperMock->fetchById(1));
    }
}
