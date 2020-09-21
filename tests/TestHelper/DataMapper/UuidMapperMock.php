<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2020, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\TestHelper\DataMapper;

use InvalidArgumentException;
use Linna\DataMapper\UUID4;
use Linna\DataMapper\UuidDomainObjectInterface;
use Linna\DataMapper\FetchByNameInterface;
use Linna\DataMapper\FetchAllInterface;
use Linna\DataMapper\FetchLimitInterface;
use Linna\DataMapper\UuidMapperAbstract;
use Linna\DataMapper\UuidMapperInterface;
use Linna\DataMapper\NullUuidDomainObject;

/**
 * MapperMock.
 */
class UuidMapperMock extends UuidMapperAbstract implements UuidMapperInterface, FetchByNameInterface, FetchAllInterface, FetchLimitInterface
{
    /**
     * @var array Mock storage
     */
    private $storage = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        //void method
    }

    /**
     * Fetch a domain object by id.
     *
     * @param int $objectId
     *
     * @return UuidDomainObjectInterface
     */
    public function fetchById(string $objectId): UuidDomainObjectInterface
    {
        return $this->storage[$objectId] ?? new NullUuidDomainObject();
    }

    /**
     * Fetch a domain object by name.
     *
     * @param string $objectName
     *
     * @return UuidDomainObjectInterface
     */
    public function fetchByName(string $objectName): UuidDomainObjectInterface
    {
        foreach ($this->storage as $key => $object) {
            if ($object->name === $objectName) {
                return $this->storage[$key];
            }
        }

        return new NullUuidDomainObject();
    }

    /**
     * Fetch all object stored in persistent storage.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        return $this->storage;
    }

    /**
     * Fetch domain objects with limit.
     *
     * @param int $offset   Offset of the first row to return
     * @param int $rowCount Maximum number of rows to return
     *
     * @return array
     */
    public function fetchLimit(int $offset, int $rowCount): array
    {
    }

    /**
     * Create a new instance of a domain object.
     *
     * @return UuidDomainObjectInterface
     */
    protected function concreteCreate(): UuidDomainObjectInterface
    {
        return new UuidDomainObjectMock();
    }

    /**
     * Insert a domain object object to persistent storage.
     * Domain object passed as reference, gain the id of the persistent
     * storage record.
     *
     * @param UuidDomainObjectInterface $domainObjectMock
     */
    protected function concreteInsert(UuidDomainObjectInterface &$domainObjectMock)
    {
        $this->checkDomainObjectType($domainObjectMock);

        $id = (new UUID4())->getHex();

        $domainObjectMock->setId($id);

        $this->storage[$id] = $domainObjectMock;
    }

    /**
     * Update a domain object in persistent storage.
     *
     * @param UuidDomainObjectInterface $domainObjectMock
     */
    protected function concreteUpdate(UuidDomainObjectInterface $domainObjectMock)
    {
        $this->checkDomainObjectType($domainObjectMock);

        $id = $domainObjectMock->getId();

        $this->storage[$id] = $domainObjectMock;
    }

    /**
     * Delete a domain object from peristent Storage.
     * Domain object passed as reference, become NullDomainObject after
     * deletion.
     *
     * @param UuidDomainObjectInterface $domainObject
     */
    protected function concreteDelete(UuidDomainObjectInterface &$domainObjectMock)
    {
        $this->checkDomainObjectType($domainObjectMock);

        $id = $domainObjectMock->getId();

        unset($this->storage[$id]);

        $domainObjectMock = new NullUuidDomainObject();
    }

    /**
     * Check for valid domain Object.
     *
     * @param UuidDomainObjectInterface $domainObject
     *
     * @throws InvalidArgumentException if the domain object isn't of the type required by mapper
     */
    protected function checkDomainObjectType(UuidDomainObjectInterface $domainObject)
    {
        if (!($domainObject instanceof UuidDomainObjectMock)) {
            throw new InvalidArgumentException('Domain Object parameter must be instance of UuidDomainObjectMock class');
        }
    }
}
