<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DataMapper;

/**
 * Abstract Class for ObjectMapper.
 */
abstract class MapperAbstract
{
    /**
     * Create a new instance of the DomainObject that this
     * mapper is responsible for.
     *
     * @return DomainObjectAbstract
     */
    public function create()
    {
        return $this->concreteCreate();
    }

    /**
     * Store the DomainObject in persistent storage. Either insert
     * or update the store as required.
     *
     * @param DomainObjectAbstract $domainObject
     */
    public function save(DomainObjectInterface $domainObject)
    {
        if ($domainObject->getId() === 0) {
            return $this->concreteInsert($domainObject);
        }

        return $this->concreteUpdate($domainObject);
    }

    /**
     * Delete the DomainObject from persistent storage.
     *
     * @param DomainObjectAbstract $domainObject
     */
    public function delete(DomainObjectInterface $domainObject)
    {
        return $this->concreteDelete($domainObject);
    }

    /**
     * Create a new instance of a DomainObject.
     *
     * @return DomainObjectAbstract
     */
    abstract protected function concreteCreate() : DomainObjectInterface;

    /**
     * Insert the DomainObject to persistent storage.
     *
     * @param DomainObjectAbstract $domainObject
     */
    abstract protected function concreteInsert(DomainObjectInterface $domainObject);

    /**
     * Update the DomainObject in persistent storage.
     *
     * @param DomainObjectAbstract $domainObject
     */
    abstract protected function concreteUpdate(DomainObjectInterface $domainObject);

    /**
     * Delete the DomainObject from peristent Storage.
     *
     * @param DomainObjectAbstract $domainObject
     */
    abstract protected function concreteDelete(DomainObjectInterface $domainObject);
}
