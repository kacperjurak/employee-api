<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Employee;

class EmployeeDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @param DataPersisterInterface $decoratedDataPersister
     */
    public function __construct(private DataPersisterInterface $decoratedDataPersister)
    {
    }

    /**
     * @param $data
     * @param array $context
     * @return bool
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Employee;
    }

    /**
     * @param $data
     * @param array $context
     * @return object|void
     */
    public function persist($data, array $context = [])
    {
        /** @var Employee $data */
        $data->setPassword(password_hash($data->getPassword(), PASSWORD_DEFAULT));
        return $this->decoratedDataPersister->persist($data, $context);
    }

    /**
     * @param $data
     * @param array $context
     * @return mixed
     */
    public function remove($data, array $context = []): mixed
    {
        return $this->decoratedDataPersister->remove($data, $context);
    }
}
