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
//use Linna\DataMapper\DomainObjectAbstract;
use Linna\DataMapper\DomainObjectInterface;
use Linna\DataMapper\FetchByNameInterface;
use Linna\DataMapper\FetchAllInterface;
use Linna\DataMapper\FetchLimitInterface;
use Linna\DataMapper\MapperAbstract;
use Linna\DataMapper\MapperInterface;
use Linna\DataMapper\NullDomainObject;

/**
 * MapperMock.
 */
class MapperMock extends MapperAbstract implements MapperInterface, FetchByNameInterface, FetchAllInterface, FetchLimitInterface
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
     * @return DomainObjectInterface
     */
    public function fetchById(int $objectId): DomainObjectInterface
    {
        return $this->storage[$objectId] ?? new NullDomainObject();
    }

    /**
     * Fetch a domain object by name.
     *
     * @param string $objectName
     *
     * @return DomainObjectInterface
     */
    public function fetchByName(string $objectName): DomainObjectInterface
    {
        foreach ($this->storage as $key => $object) {
            if ($object->name === $objectName) {
                return $this->storage[$key];
            }
        }

        return new NullDomainObject();
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
     * @return DomainObjectInterface
     */
    protected function concreteCreate(): DomainObjectInterface
    {
        return new DomainObjectMock();
    }

    /**
     * Insert a domain object object to persistent storage.
     * Domain object passed as reference, gain the id of the persistent
     * storage record.
     *
     * @param DomainObjectInterface $domainObjectMock
     */
    protected function concreteInsert(DomainObjectInterface &$domainObjectMock)
    {
        $this->checkDomainObjectType($domainObjectMock);

        $id = \array_key_last($this->storage);

        if (\is_null($id)) {
            $id = 0;
        }

        $domainObjectMock->setId(++$id);

        $this->storage[$id] = $domainObjectMock;
    }

    /**
     * Update a domain object in persistent storage.
     *
     * @param DomainObjectInterface $domainObjectMock
     */
    protected function concreteUpdate(DomainObjectInterface $domainObjectMock)
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
     * @param DomainObjectInterface $domainObject
     */
    protected function concreteDelete(DomainObjectInterface &$domainObjectMock)
    {
        $this->checkDomainObjectType($domainObjectMock);

        $id = $domainObjectMock->getId();

        unset($this->storage[$id]);

        $domainObjectMock = new NullDomainObject();
    }

    /**
     * Check for valid domain Object.
     *
     * @param DomainObjectInterface $domainObject
     *
     * @throws InvalidArgumentException if the domain object isn't of the type required by mapper
     */
    protected function checkDomainObjectType(DomainObjectInterface $domainObject)
    {
        if (!($domainObject instanceof DomainObjectMock)) {
            throw new InvalidArgumentException('Domain Object parameter must be instance of DomainObjectMock class');
        }
    }
}
