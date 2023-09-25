<?php

namespace App\Repository;

use App\DataModel\InputDataModel;
use App\Entity\Client;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class ClientRepository extends EntityRepository
{
    /**
     * @throws NonUniqueResultException
     */
    public function getExisitingClient(InputDataModel $inputDataModel): ?Client
    {
        return $this->createQueryBuilder('c')
            ->where('c.deleted = false')
            ->andWhere('c.clientId = :clientId')
            ->orWhere('c.email = :email')
            ->setParameter('clientId', $inputDataModel->getClientId())
            ->setParameter('email', $inputDataModel->getEmail())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}