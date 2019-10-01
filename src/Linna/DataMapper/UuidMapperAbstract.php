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

use InvalidArgumentException;

/**
 * Abstract Class for ObjectMapper, uuid version.
 */
abstract class UuidMapperAbstract
{
    /**
     * Create a new instance of the DomainObject that this
     * mapper is responsible for.
     *
     * @return UuidDomainObjectInterface
     */
    public function create(): UuidDomainObjectInterface
    {
        return $this->concreteCreate();
    }

    /**
     * Store the DomainObject in persistent storage. Either insert
     * or update the store as required.
     *
     * @param UuidDomainObjectInterface $domainObject
     *
     * @return void
     */
    public function save(UuidDomainObjectInterface &$domainObject): void
    {
        if ($domainObject->getId() === '') {
            $this->concreteInsert($domainObject);
        }

        $this->concreteUpdate($domainObject);
    }

    /**
     * Delete the DomainObject from persistent storage.
     *
     * @param UuidDomainObjectInterface $domainObject
     *
     * @return void
     */
    public function delete(UuidDomainObjectInterface &$domainObject): void
    {
        $this->concreteDelete($domainObject);
    }

    /**
     * Create a new instance of a DomainObject.
     *
     * @return UuidDomainObjectInterface
     */
    abstract protected function concreteCreate(): UuidDomainObjectInterface;

    /**
     * Insert the DomainObject to persistent storage.
     *
     * @param UuidDomainObjectInterface $domainObject
     */
    abstract protected function concreteInsert(UuidDomainObjectInterface &$domainObject);

    /**
     * Update the DomainObject in persistent storage.
     *
     * @param UuidDomainObjectInterface $domainObject
     */
    abstract protected function concreteUpdate(UuidDomainObjectInterface $domainObject);

    /**
     * Delete the DomainObject from peristent Storage.
     *
     * @param UuidDomainObjectInterface $domainObject
     */
    abstract protected function concreteDelete(UuidDomainObjectInterface &$domainObject);

    /**
     * Check for valid domain Object.
     *
     * @param UuidDomainObjectInterface $domainObject
     *
     * @throws InvalidArgumentException if the domain object isn't of the type required by mapper
     */
    abstract protected function checkDomainObjectType(UuidDomainObjectInterface $domainObject);
}
