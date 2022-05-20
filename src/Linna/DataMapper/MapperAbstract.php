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

use InvalidArgumentException;

/**
 * Abstract Class for ObjectMapper.
 */
abstract class MapperAbstract
{
    /**
     * Create a new instance of the DomainObject that this
     * mapper is responsible for.
     *
     * @return DomainObjectInterface
     */
    public function create(): DomainObjectInterface
    {
        return $this->concreteCreate();
    }

    /**
     * Store the DomainObject in persistent storage. Either insert
     * or update the store as required.
     *
     * @param DomainObjectInterface $domainObject
     *
     * @return void
     */
    public function save(DomainObjectInterface &$domainObject): void
    {
        if ($domainObject->getId() === null) {
            $this->concreteInsert($domainObject);
            return;
        }

        $this->concreteUpdate($domainObject);
    }

    /**
     * Delete the DomainObject from persistent storage.
     *
     * @param DomainObjectInterface $domainObject
     *
     * @return void
     */
    public function delete(DomainObjectInterface &$domainObject): void
    {
        $this->concreteDelete($domainObject);
    }

    /**
     * Create a new instance of a DomainObject.
     *
     * @return DomainObjectInterface
     */
    abstract protected function concreteCreate(): DomainObjectInterface;

    /**
     * Insert the DomainObject to persistent storage.
     *
     * @param DomainObjectInterface $domainObject
     *
     * @return void
     */
    abstract protected function concreteInsert(DomainObjectInterface &$domainObject): void;

    /**
     * Update the DomainObject in persistent storage.
     *
     * @param DomainObjectInterface $domainObject
     *
     * @return void
     */
    abstract protected function concreteUpdate(DomainObjectInterface $domainObject): void;

    /**
     * Delete the DomainObject from peristent Storage.
     *
     * @param DomainObjectInterface $domainObject
     *
     * @return void
     */
    abstract protected function concreteDelete(DomainObjectInterface &$domainObject): void;

    /**
     * Check for valid domain Object.
     *
     * @param DomainObjectInterface $domainObject
     *
     * @return void
     *
     * @throws InvalidArgumentException if the domain object isn't of the type required by mapper
     */
    abstract protected function checkDomainObjectType(DomainObjectInterface $domainObject): void;
}
